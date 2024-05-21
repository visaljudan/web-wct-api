<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptionPlans = SubscriptionPlan::all();
        if ($subscriptionPlans->count() > 0) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'subscriptionPlans' => $subscriptionPlans
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'No Record Found',
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'subscription_plan_name' => 'required|string|unique:subscription_plans',
            'subscription_plan_description' => 'required|string',
            'subscription_plan_price' => 'required|numeric',
            'subscription_plan_duration' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $subscriptionPlan = SubscriptionPlan::where('subscription_plan_name', $request->subscription_plan_name)->first();
        if ($subscriptionPlan) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Subscription plan name is already used!',
            ], 422);
        }


        // Create a new subscription plan record
        $subscriptionPlan = SubscriptionPlan::create([
            'subscription_plan_name' => $request->input('subscription_plan_name'),
            'subscription_plan_description' => $request->input('subscription_plan_description'),
            'subscription_plan_price' => $request->input('subscription_plan_price'),
            'subscription_plan_duration' => $request->input('subscription_plan_duration'),
        ]);

        // Return a success response
        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'message' => 'Subscription plan created successfully',
            'subscription_plan' => $subscriptionPlan,
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subscriptionPlan = SubscriptionPlan::find($id);

        // If subscription plan not found, return error response
        if (!$subscriptionPlan) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Subscription plan not found',
            ], 404);
        }

        // Return the subscription plan data
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'subscription_plan' => $subscriptionPlan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the subscription plan by ID
        $subscriptionPlan = SubscriptionPlan::find($id);

        // If subscription plan not found, return error response
        if (!$subscriptionPlan) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Subscription plan not found',
            ], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'subscription_plan_name' => 'required|string|max:255|unique:subscription_plans,subscription_plan_name,' . $id,
            'subscription_plan_description' => 'required|string',
            'subscription_plan_price' => 'required|numeric',
            'subscription_plan_duration' => 'required|integer',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update subscription plan attributes with new values
        $subscriptionPlan->subscription_plan_name = $request->input('subscription_plan_name');
        $subscriptionPlan->subscription_plan_description = $request->input('subscription_plan_description');
        $subscriptionPlan->subscription_plan_price = $request->input('subscription_plan_price');
        $subscriptionPlan->subscription_plan_duration = $request->input('subscription_plan_duration');

        // Save the updated subscription plan
        $subscriptionPlan->save();

        // Return success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Subscription plan updated successfully',
            'subscription_plan' => $subscriptionPlan,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subscriptionPlan = SubscriptionPlan::find($id);

        // If subscription plan not found, return error response
        if (!$subscriptionPlan) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Subscription plan not found',
            ], 404);
        }

        // Delete the subscription plan
        $subscriptionPlan->delete();

        // Return success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Subscription plan deleted successfully',
        ], 200);
    }
}
