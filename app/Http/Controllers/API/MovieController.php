<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Movie\MovieResource;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends MainController
{
    /**
 * @OA\Get(
 *     path="/api/movies",
 *     tags={"Movies"},
 *     summary="Get List movies Data",
 *     description="enter your movies here",
 *     operationId="movies",
 *     @OA\Response(
 *         response="default",
 *         description="return array model movies"
 *     )
 * )
 */
 
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
 * @OA\Post(
 *     path="/api/movies",
 *     tags={"Movies"},
 *     summary="movies",
 *     description="movies",
 *     operationId="Movies",
 *     @OA\RequestBody(
 *          required=true,
 *          description="form movies",
 *          @OA\JsonContent(
 *            required={"title", "overview", "run_time", "release_date", "total_likes", "total_ratings",
 *                      "average_rating", "poster_image", "cover_image", "trailer_url", "last_upload_date",
 *                       "subscription_only", "expired_subscription_only"},
 *              @OA\Property(property="title", type="string"),
 *              @OA\Property(property="overview", type="string"),
                * @OA\Property(property="run_time", type="integer"),
                * @OA\Property(property="release_date", type="date"),
                 *@OA\Property(property="total_likes", type="integer"),
                * @OA\Property(property="total_ratings", type="integer"),
                * @OA\Property(property="average_rating", type="string"),
                * @OA\Property(property="poster_image", type="string"),
                * @OA\Property(property="cover_image", type="string"),
                *@OA\Property(property="trailer_url", type="string"),
                *@OA\Property(property="last_upload_date", type="date"),
                *@OA\Property(property="subscription_only", type="string"),
                *@OA\Property(property="expired_subscription_only", type="date"),
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
    //  * Store a newly created resource in storage.
    //  */
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
     * @OA\Get(
     *     path="/api/movies/{id}",
     *     tags={"Movies"},
     *     summary="Detail",
     *     description="-",
     *     operationId="Movies/GetById",
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
    //  * Display the specified resource.
    //  */
    public function show($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return $this->sendError(404, "Movie not found");
        }

        $res = new MovieResource($movie);
        return $this->sendSuccess(200, "Movie have found", $res);
    }
/**
     * @OA\Put(
     *     path="/api/movies/{id}",
     *     tags={"Movies"},
     *     summary="Update movies",
     *     description="-",
     *     operationId="movies/update",
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
     *             required={"title", "overview", "run_time", "release_date", "total_likes", "total_ratings",
 *                      "average_rating", "poster_image", "cover_image", "trailer_url", "last_upload_date",
 *                       "subscription_only", "expired_subscription_only"},
 *              @OA\Property(property="title", type="string"),
 *              @OA\Property(property="overview", type="string"),
                * @OA\Property(property="run_time", type="integer"),
                * @OA\Property(property="release_date", type="date"),
                 *@OA\Property(property="total_likes", type="integer"),
                * @OA\Property(property="total_ratings", type="integer"),
                * @OA\Property(property="average_rating", type="string"),
                * @OA\Property(property="poster_image", type="string"),
                * @OA\Property(property="cover_image", type="string"),
                *@OA\Property(property="trailer_url", type="string"),
                *@OA\Property(property="last_upload_date", type="date"),
                *@OA\Property(property="subscription_only", type="string"),
                *@OA\Property(property="expired_subscription_only", type="date"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    // /**
    //  * Update the specified resource in storage.
    //  */
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
     * @OA\Delete(
     *     path="/api/movies/{id}",
     *     tags={"Movies"},
     *     summary="Delete movies",
     *     description="-",
     *     operationId="movies/delete",
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
    //  * Remove the specified resource from storage.
    //  */
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
