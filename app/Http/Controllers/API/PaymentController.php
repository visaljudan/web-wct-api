<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends MainController
{

    public function charge(Request $request)
    {
        // Get the token from the request header
        $token = $request->header('Authorization');

        if (!$token) {
            return $this->sendError(401, 'Token is missing in the request header');
        }

        // Remove "Bearer " prefix from the token
        $tokenValue = str_replace('Bearer ', '', $token);

        // Find the user associated with the token
        $user = User::where('api_token', $tokenValue)->first();

        if (!$user) {
            return $this->sendError(401, 'Invalid token');
        }

        // Check if the user's role is "User" or "Admin"
        if (!Gate::allows('adminUser', $user)) {
            return $this->sendError(403, 'You are not allowed');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = $request->stripeToken;

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string',
            'expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => 'required|numeric|digits:3',
            'name' => 'nullable|string',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'description' => 'nullable|string',
            'stripeToken' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Retrieve subscription plan
        $subscriptionPlan = SubscriptionPlan::find($request->subscription_plan_id);
        if (!$subscriptionPlan) {
            return $this->sendError(404, 'Subscription plan not found');
        }

        $amount = $subscriptionPlan->subscription_plan_price;

        // Get the length of the card number
        $cardNumberLength = strlen($request->card_number);

        // Get the last four digits of the card number
        $lastFourDigits = substr($request->card_number, -4);

        // Mask the digits before the last four with asterisks
        $maskedCardNumber = str_repeat('*', $cardNumberLength - 4) . $lastFourDigits;

        try {
            // Create charge using Stripe API
            $charge = \Stripe\Charge::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'description' => $request->description ?? 'Subscription Charge', // Use provided description or default
                'source' => $token,
            ]);

            // Check if charge is successful
            if ($charge->status === "succeeded") {
                // Create a payment record
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $request->subscription_plan_id,
                    'payment_status' => 'Success',
                    'description' => $request->description,
                    'transaction_id' => $charge->id,
                    'payment_method' => $charge->payment_method,
                    'receipt_url' => $charge->receipt_url,
                    'card_number' => $maskedCardNumber,
                    'expiry' => $request->expiry,
                    'name' => $request->name,
                ]);

                // Update user's role to 'User Subscription' if not Admin
                if ($user->role !== 'Admin') {
                    $user->role = 'User Subscription';
                    $user->save();
                }

                // Store subscription details in user_subscription table
                UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $request->subscription_plan_id,
                    'subscription_start_date' => now(),
                    'subscription_end_date' => now()->addDays(30),
                    'subscription_status' => 'running',
                ]);

                return $this->sendSuccess(200, 'Payment success', $payment);
            } else {
                return $this->sendError(500, 'Payment failed');
            }
        } catch (\Exception $e) {
            return $this->sendError(500, 'Internal Server Error', $e->getMessage());
        }
    }



    /**
     * @OA\Get(
     *     path="/api/payments",
     *     tags={"Payments"},
     *     summary="Get List Artists Data",
     *     description="enter your Artists here",
     *     operationId="payments",
     *     @OA\Response(
     *         response="default",
     *         description="return array model payments"
     *     )
     * )
     */
    // /**
    //  * Display a listing of the resource.
    //  */
    public function index()
    {
        $payments = Payment::all();

        if ($payments->count() > 0) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'payments' => $payments
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'statusCode' => 400,
                'message' => 'No Record Found'
            ], 400);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/payments",
     *     tags={"Payments"},
     *     summary="payments",
     *     description="payments",
     *     operationId="Payments",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form payments",
     *          @OA\JsonContent(
     *            required={"user_id", "subscription_plan_id", "payment_status"},
     *              @OA\Property(property="user_id", type="string"),
     *              @OA\Property(property="subscription_plan_id", type="string"),
     *              @OA\Property(property="payment_status", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *        
     *     )
     * )
     */
    // /**
    //  * Store a newly created payment in storage.
    //  */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'payment_status' => 'required|in:Success,Fail'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $existingPayment = Payment::where('user_id', $request->user_id)
            ->where('payment_status', 'Success')
            ->first();

        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Cannot add more payments while previous payment is successful.',
            ], 422);
        }

        $payment = Payment::create($request->all());

        if ($request->payment_status === 'Fail') {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Payment failed.',
            ], 422);
        }

        $user = User::find($request->user_id);

        $subscriptionPlan = SubscriptionPlan::find($request->subscription_plan_id);

        $user->role = 'Subscription User';

        $user->subscription_plan_id = $request->subscription_plan_id;

        $user->subscription_start_date = now();

        $user->subscription_end_date = now()->addDays($subscriptionPlan->subscription_plan_duration);

        $user->save();

        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'payment' => $payment
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}",
     *     tags={"Payments"},
     *     summary="Detail",
     *     description="-",
     *     operationId="payments/GetById",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="return model admin"
     *     )
     * )
     */
    // /**
    //  * Display the specified payment.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function show($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'payment' => $payment
        ], 200);
    }
    /**
     * @OA\Delete(
     *     path="/api/payments/{id}",
     *     tags={"Payments"},
     *     summary="Delete payments",
     *     description="-",
     *     operationId="payments/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    // /**
    //  * Remove the specified payment from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully'
        ]);
    }
}
