<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends MainController
{

    public function charge(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = $request->stripeToken;

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'card_number' => 'required|string',
            'expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => 'required|numeric|digits:3',
            'name' => 'nullable|string',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'stripeToken' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Get the length of the card number
        $cardNumberLength = strlen($request->card_number);

        // Get the last four digits of the card number
        $lastFourDigits = substr($request->card_number, -4);

        // Mask the digits before the last four with asterisks
        $maskedCardNumber = str_repeat('*', $cardNumberLength - 4) . $lastFourDigits;
        try {
            // Create charge using Stripe API
            $charge = \Stripe\Charge::create([
                'amount' => $request->amount * 100, // Convert to cents
                'currency' => 'usd',
                'description' => $request->description, // Use provided description or default
                'source' => $token,
            ]);

            // Check if charge is successful
            if ($charge->status === "succeeded") {
                // Create a payment record
                $payment = Payment::create([
                    'user_id' => $request->user_id,
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

                // Update user's role to 'premium'
                $user = User::find($request->user_id);
                $user->role = 'User Subcription';
                $user->save();

                // Store subscription details in user_subscription table
                UserSubscription::create([
                    'user_id' => $request->user_id,
                    'subscription_plan_id' => $request->subscription_plan_id,
                    'subscription_start_date' => now(),
                    'subscription_end_date' => now()->addMonths(1),
                    'subscription_status' => 'running',
                ]);
            }
            return $this->sendSuccess(200, 'Payment success', $payment);
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
