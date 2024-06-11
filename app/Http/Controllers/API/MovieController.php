<?php

namespace App\Http\Controllers\API;

use App\Events\MessageSent;
use App\Http\Controllers\MainController;
use App\Http\Resources\Movie\MovieResource;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\NewMovieEvent;
use App\Http\Resources\Movie\MovieResourceCollection;
use App\Http\Resources\MovieGenre\MovieGenreResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;

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
            $res = new MovieResourceCollection($movies);
            return $this->sendSuccess(200, 'Movie found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
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
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $movie = Movie::create($request->all());

        $res = new MovieResource($movie);
        return $this->sendSuccess(201, 'Movie created successfully', $res);
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
    public function show($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return $this->sendError(404, "Movie not found");
        }

        $res = new MovieResource($movie);
        return $this->sendSuccess(200, "Movie found", $res);
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
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return $this->sendError(404, 'Movie not found');
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
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $movie->update($request->all());

        $res = new MovieResource($movie);
        return $this->sendSuccess(200, 'Movie updated successfully', $res);
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
    public function destroy($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return $this->sendError(404, 'Movie not found');
        }

        $movie->delete();

        return $this->sendSuccess(2000, 'Movie deleted successfully');
    }

    public function latest()
    {
        $latestMovies = Movie::orderBy('release_date', 'desc')->get();

        if ($latestMovies->isEmpty()) {
            return $this->sendError(404, 'No latest movies found');
        }

        $res = new MovieResourceCollection($latestMovies);
        return $this->sendSuccess(200, 'Latest movies found', $latestMovies);
    }

    public function popular()
    {
        $popularMovies = Movie::orderBy('popularity', 'desc')->get();

        if ($popularMovies->isEmpty()) {
            return $this->sendError(404, 'No popular movies found');
        }

        $res = new MovieResourceCollection($popularMovies);
        return $this->sendSuccess(200, 'Latest movies found', $res);
    }

    public function topRated()
    {
        $topRatedMovies = Movie::orderBy('average_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->get();

        if ($topRatedMovies->isEmpty()) {
            return $this->sendError(404, 'No top-rated movies found');
        }

        $res = new MovieResourceCollection($topRatedMovies);
        return $this->sendSuccess(200, 'Top-rated movies retrieved successfully', $res);
    }


    public function tvShow($tvShowId)
    {
        $movies = Movie::where('tv_show_id', $tvShowId)->get();

        if ($movies->isEmpty()) {
            return $this->sendError(404, 'TV Show genres not found');
        }

        $res = new MovieResourceCollection($movies);
        return $this->sendSuccess(200, 'TV Show Genres Found', $res);
    }


    public function year($year)
    {
        // Log the year input for debugging
        Log::info('Year input: ' . $year);

        // Fetch movies where the release_date year matches the input year
        $movies = Movie::whereYear('release_date', $year)->get();

        // Log the count of movies found
        Log::info('Movies found: ' . $movies->count());

        // Check if movies collection is empty
        if ($movies->isEmpty()) {
            return $this->sendError(404, 'Movies from this year not found');
        }

        // Create a resource collection of the found movies
        $res = new MovieResourceCollection($movies);

        // Log the success response
        Log::info('Success response: Movies from this year found');

        // Return the success response
        return $this->sendSuccess(200, 'Movies from this year found', $movies);
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


    public function search(Request $request)
    {
        $query = $request->input('query');
        $query = strtolower($query); // Convert search query to lowercase

        $movies = Movie::whereRaw('LOWER(title) LIKE ?', ['%' . $query . '%'])->get();

        if ($movies->isEmpty()) {
            return $this->sendError(404, 'No movies found matching the criteria');
        }

        $res = new MovieResourceCollection($movies);
        return $this->sendSuccess(200, 'Movies found', $res);
    }


    public function filter(Request $request)
    {
        // Validate the search request
        $request->validate([
            'title' => 'nullable|string',
            'genre' => 'nullable|string',
            'director' => 'nullable|string',
            'release_year' => 'nullable|integer',
            'rating' => 'nullable|numeric|min:0|max:10',
        ]);

        // Build the query based on provided parameters
        $query = Movie::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->has('genre')) {
            $query->where('genre', 'like', '%' . $request->input('genre') . '%');
        }

        if ($request->has('year')) {
            $query->where('director', 'like', '%' . $request->input('director') . '%');
        }

        if ($request->has('release_year')) {
            $query->whereYear('release_date', $request->input('release_year'));
        }

        if ($request->has('rating')) {
            $query->where('average_rating', '>=', $request->input('rating'));
        }

        // Get the results
        $movies = $query->get();

        // Check if any movies were found
        if ($movies->isEmpty()) {
            return $this->sendError(404, 'No movies found matching the criteria');
        }

        // Create a resource collection of the found movies
        $res = new MovieResourceCollection($movies);

        // Return the success response
        return $this->sendSuccess(200, 'Movies found', $res);
    }
}
