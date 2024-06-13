<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Resources\SubscriptionPlan\SubscriptionPlanResource;
use App\Http\Resources\SubscriptionPlan\SubscriptionPlanResourceCollection;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;


class SubscriptionPlanController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/subscription_plans",
     *     tags={"Subscription-Plans"},
     *     summary="Get List subscription-plans Data",
     *     description="enter your subscription-plans here",
     *     operationId="subscription-plans",
     *     @OA\Response(
     *         response="default",
     *         description="return array model subscription-plans"
     *     )
     * )
     */
    public function index()
    {
        $subscriptionPlans = SubscriptionPlan::all();

        if ($subscriptionPlans->count() > 0) {
            $res = new SubscriptionPlanResourceCollection($subscriptionPlans);
            return $this->sendSuccess(200, 'Subsctiption plan found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }
    /**
     * @OA\Post(
     *     path="/api/subscription_plans",
     *     tags={"Subscription-Plans"},
     *     summary="subscription-plans",
     *     description="subscription-plans",
     *     operationId="Subscription-Plans",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form subscription-plans",
     *          @OA\JsonContent(
     *            required={"subscription_plan_name", "subscription_plan_description", "subscription_plan_price", "subscription_plan_duration"},
     *              @OA\Property(property="subscription_plan_name", type="string"),
     *              @OA\Property(property="subscription_plan_description", type="string"),
     * @OA\Property(property="subscription_plan_price", type="string"),
     * @OA\Property(property="subscription_plan_duration", type="string"),
     *          ),
     *      ),
     *    @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */
    public function store(Request $request)
    {
        // Define validation rules for the incoming request
        $validator = Validator::make($request->all(), [
            'subscription_plan_name' => 'required|string|unique:subscription_plans',
            'subscription_plan_description' => 'required|string',
            'subscription_plan_price' => 'required|numeric',
            'subscription_plan_duration' => 'required|integer',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Check if the current user is authorized to create a subscription plan
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        // Create the subscription plan using the request data
        $subscriptionPlan = SubscriptionPlan::create($request->all());

        // Return a success response with the created subscription plan data
        $res = new SubscriptionPlanResource($subscriptionPlan);
        return $this->sendSuccess(201, 'Subscription plan created successfully', $res);
    }

    /**
     * @OA\Get(
     *     path="/api/subscription_plans/{id}",
     *     tags={"Subscription-Plans"},
     *     summary="Detail",
     *     description="-",
     *     operationId="subscription-plans/GetById",
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
    public function show($id)
    {
        // Find the subscription plan by its ID
        $subscriptionPlan = SubscriptionPlan::find($id);

        // Check if the subscription plan exists
        if (!$subscriptionPlan) {
            return $this->sendError(404, 'Subscription plan not found');
        }

        // Return a success response with the transformed subscription plan data
        $res = new SubscriptionPlanResource($subscriptionPlan);
        return $this->sendSuccess(200, 'Subscription plan found', $res);
    }

    /**
     * @OA\Put(
     *     path="/api/subscription_plans/{id}",
     *     tags={"Subscription-Plans"},
     *     summary="Update subscription-plans",
     *     description="-",
     *     operationId="subscription-plans/update",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="form admin",
     *          @OA\JsonContent(
     *             required={"subscription_plan_name", "subscription_plan_description", "subscription_plan_price", "subscription_plan_duration"},
     *              @OA\Property(property="subscription_plan_name", type="string"),
     *              @OA\Property(property="subscription_plan_description", type="string"),
     * @OA\Property(property="subscription_plan_price", type="numeric"),
     * @OA\Property(property="subscription_plan_duration", type="integer"),
     *          ),
     *      ),
     *    @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */
    public function update(Request $request, $id)
    {
        // Find the subscription plan by ID
        $subscriptionPlan = SubscriptionPlan::find($id);

        // If subscription plan not found, return error response
        if (!$subscriptionPlan) {
            return $this->sendError(404, 'Subscription plan not found');
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
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Check if the current user is authorized to create a subscription plan
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        // Update subscription plan attributes with new values
        $subscriptionPlan->subscription_plan_name = $request->input('subscription_plan_name');
        $subscriptionPlan->subscription_plan_description = $request->input('subscription_plan_description');
        $subscriptionPlan->subscription_plan_price = $request->input('subscription_plan_price');
        $subscriptionPlan->subscription_plan_duration = $request->input('subscription_plan_duration');

        // Save the updated subscription plan
        $subscriptionPlan->save();

        // Return success response
        $res = new SubscriptionPlanResource($subscriptionPlan);
        return $this->sendSuccess(200, 'Subscription plan updated successfully', $res);
    }

    /**
     * @OA\Delete(
     *     path="/api/subscription_plans/{id}",
     *     tags={"Subscription-Plans"},
     *     summary="Delete subscription-plans",
     *     description="-",
     *     operationId="subscription-plans/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *    @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */
    public function destroy(string $id)
    {
        // Find the subscription plan by ID
        $subscriptionPlan = SubscriptionPlan::find($id);

        // If subscription plan not found, return error response
        if (!$subscriptionPlan) {
            return $this->sendError(404, 'Subscription plan not found');
        }

        // Check if the current user is authorized to create a subscription plan
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        // Delete the subscription plan
        $subscriptionPlan->delete();

        // Return success response
        return $this->sendSuccess(200, 'Subscription plan deleted successfully');
    }
}
