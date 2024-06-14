<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class UserSubscriptionController extends MainController
{
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

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

        // Find the running subscriptions for the user
        $subscriptions = UserSubscription::where('user_id', $user->id)
            ->where('subscription_status', 'running')
            ->first();

        if (!$subscriptions) {
            return $this->sendError(404, 'No running subscriptions found for the user');
        }

        // Return a success response with the subscriptions
        return $this->sendSuccess(200, 'Running subscriptions found', $subscriptions);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
