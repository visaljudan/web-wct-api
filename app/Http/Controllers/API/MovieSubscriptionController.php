<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MovieSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movieSubscriptions = MovieSubscription::all();
        if ($movieSubscriptions->count() > 0) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'genres' => $movieSubscriptions
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'status' => 'required|string',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $movieSubscription = MovieSubscription::create($request->all());

        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'message' => 'Movie subscription created successfully',
            'movieSubscription' => $movieSubscription,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movieSubscription = MovieSubscription::find($id);

        if (!$movieSubscription) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie subscription not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'movieSubscription' => $movieSubscription,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $movieSubscription = MovieSubscription::find($id);

        if (!$movieSubscription) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie subscription not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'exists:movies,id',
            'status' => 'string',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $movieSubscription->update($request->all());

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Movie subscription updated successfully',
            'movieSubscription' => $movieSubscription,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $movieSubscription = MovieSubscription::find($id);

        if (!$movieSubscription) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie subscription not found',
            ], 404);
        }

        $movieSubscription->delete();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Movie subscription deleted successfully',
        ], 200);
    }
}
