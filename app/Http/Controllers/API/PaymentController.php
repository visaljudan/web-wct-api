<?php
//API done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Payment\PaymentResource;
use App\Http\Resources\Payment\PaymentResourceCollection;
use App\Http\Resources\User\UserResource;
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
    public function index(Request $request)
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

        // Check if the user is an admin
        if ($user->role === 'Admin') {
            // Return all payments for admin
            $payments = Payment::all();
        } else {
            // Return only payments associated with the user
            $payments = Payment::where('user_id', $user->id)->get();
        }

        if ($payments->isEmpty()) {
            return $this->sendError(400, 'No Records Found');
        }

        // Return the rated movies
        $res = new PaymentResourceCollection($payments);
        return $this->sendSuccess(200, 'Payments found', $res);
    }

    public function store(Request $request)
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
        $duration = $subscriptionPlan->subscription_plan_duration;

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
                    // 'stripeToken' => $token,
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
                    'payment_id' => $payment->transaction_id, // Add payment ID here
                    'subscription_plan_id' => $request->subscription_plan_id,
                    'subscription_start_date' => now(),
                    'subscription_end_date' => now()->addDays($duration),
                    'subscription_status' => 'running',
                ]);


                $res = new UserResource($user);
                return $this->sendSuccess(200, 'Payment success', $res);
            } else {
                return $this->sendError(500, 'Payment failed');
            }
        } catch (\Exception $e) {
            return $this->sendError(500, 'Internal Server Error', $e->getMessage());
        }
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
    public function show(Request $request)
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

        // Check if the user is an admin
        if ($user->role === 'Admin') {
            // Return all payments for admin
            $payments = Payment::where('payment_status', "success")->get();
            if ($payments->isEmpty()) {
                return $this->sendError(404, 'No payments found');
            }
            $res = new PaymentResourceCollection($payments);
        } else {
            // Return only payments associated with the user
            $payment = Payment::where('user_id', $user->id)
                ->where('payment_status', "success")
                ->first();
            if (!$payment) {
                return $this->sendError(404, 'Payment not found');
            }
            $res = new PaymentResource($payment);
        }

        return $this->sendSuccess(200, 'Payment(s) found', $res);
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
    public function destroy($id)
    {
        // Find the payment by ID
        $payment = Payment::find($id);

        // Check if the payment exists
        if (!$payment) {
            return $this->sendError(404, 'Payment not found');
        }

        // Check if the user is authorized to perform this action
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        // Find the associated user subscription
        $userSubscription = UserSubscription::where('user_id', $payment->user_id)
            ->where('payment_id', $payment->id)
            ->first();

        // Delete the payment
        $payment->delete();

        // Delete the user subscription if it exists
        if ($userSubscription) {
            $userSubscription->delete();
        }

        return $this->sendSuccess(200, 'Payment and associated subscription deleted successfully');
    }
}
