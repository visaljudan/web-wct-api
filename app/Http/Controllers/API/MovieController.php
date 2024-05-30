<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\User;
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

        $this->notifyNewMovie($movie);

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

    public function latest()
    {
        $latestMovies = Movie::orderBy('release_date', 'desc')->get();

        if ($latestMovies->isEmpty()) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'No latest movies found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'latest_movies' => $latestMovies,
        ], 200);
    }

    public function popular()
    {
        $popularMovies = Movie::orderBy('popularity', 'desc')->get();

        if ($popularMovies->isEmpty()) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'No popular movies found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'popular_movies' => $popularMovies,
        ], 200);
    }

    public function related($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie not found',
            ], 404);
        }

        // Example: Get related movies with the same genre
        $relatedMovies = Movie::where('genre', $movie->genre)
            ->where('id', '!=', $id) // Exclude the current movie
            ->take(5) // Limit the number of related movies
            ->get();

        if ($relatedMovies->isEmpty()) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'No related movies found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'related_movies' => $relatedMovies,
        ], 200);
    }

    public function suggestMovies(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:popular,genre',
            'value' => 'required|string', // You might need to validate this based on the 'type'
        ]);

        $type = $request->input('type');
        $value = $request->input('value');

        if ($type === 'popular') {
            // Example: Get popular movies
            $suggestedMovies = Movie::orderBy('popularity', 'desc')
                ->take(5) // Limit the number of suggested movies
                ->get();
        } elseif ($type === 'genre') {
            // Example: Get movies with the same genre
            $suggestedMovies = Movie::where('genre', $value)
                ->orderBy('popularity', 'desc')
                ->take(5) // Limit the number of suggested movies
                ->get();
        } else {
            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Invalid suggestion type',
            ], 400);
        }

        if ($suggestedMovies->isEmpty()) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'No suggested movies found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'suggested_movies' => $suggestedMovies,
        ], 200);
    }
    protected function notifyNewMovie($movie)
    {
        $subscribedUsers = User::where('subscribed', true)
            ->where('preferred_genre', $movie->genre)
            ->get();

        foreach ($subscribedUsers as $user) {
            // Send notification to each user   
            // $user->notify(new NewMovieNotification($movie));
        }
    }
}
