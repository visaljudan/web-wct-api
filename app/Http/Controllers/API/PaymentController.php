<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
     * Store a newly created payment in storage.
     */
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
     * Display the specified payment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
     * Remove the specified payment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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