<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();

        if ($movies->count() > 0) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'movies' => $movies
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
            'title' => 'required|string',
            'overview' => 'nullable|string',
            'run_time' => 'nullable|integer',
            'release_date' => 'nullable|date',
            'total_likes' => 'nullable|integer',
            'total_ratings' => 'nullable|integer',
            'average_rating' => 'nullable|numeric',
            'poster_image' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'trailer_url' => 'nullable|string',
            'last_upload_date' => 'nullable|date',
            'subscription_only' => 'nullable|boolean',
            'expired_subscription_only' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $movie = Movie::create($request->all());

        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'message' => 'Movie created successfully',
            'movie' => $movie,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'movie' => $movie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string',
            'overview' => 'nullable|string',
            'run_time' => 'nullable|integer',
            'release_date' => 'nullable|date',
            'total_likes' => 'nullable|integer',
            'total_ratings' => 'nullable|integer',
            'average_rating' => 'nullable|numeric',
            'poster_image' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'trailer_url' => 'nullable|string',
            'last_upload_date' => 'nullable|date',
            'subscription_only' => 'nullable|boolean',
            'expired_subscription_only' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $movie->update($request->all());

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Movie updated successfully',
            'movie' => $movie,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie not found',
            ], 404);
        }

        $movie->delete();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Movie deleted successfully',
        ], 200);
    }
}
