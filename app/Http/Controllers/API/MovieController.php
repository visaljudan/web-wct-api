<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Movie\MovieResource;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Movie\MovieResourceCollection;
use Illuminate\Support\Facades\Gate;

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
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'tv_show_id' => 'nullable|exists:tv_shows,id',
            'title' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'run_time' => 'nullable|integer',
            'release_date' => 'nullable|date',
            'poster_image_file' => 'nullable|required_without:poster_image_url|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust max size as per your requirements
            'poster_image_url' => 'nullable|required_without:poster_image_file|url',
            'cover_image_file' => 'nullable|required_without:cover_image_url|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust max size as per your requirements
            'cover_image_url' => 'nullable|required_without:cover_image_file|url',
            'total_raters' => 'nullable|integer',
            'total_ratings' => 'nullable|integer',
            'average_rating' => 'nullable|numeric|min:0|max:10',
            'popularity' => 'nullable|integer',
            'terms_status' => 'nullable|string|in:public,private',
            'upload_status' => 'nullable|string',
            'last_upload_date' => 'nullable|date',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Check user permissions
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        try {
            $poster_image = null;
            $cover_image = null;

            // Handle poster image file upload
            if ($request->hasFile('poster_image_file')) {
                $posterImageFile = $request->file('poster_image_file');
                $posterImagePath = $posterImageFile->store('img'); // Store in 'img' directory
                $poster_image = env('AWS_CLOUDFRONT_URL') . '/' . $posterImagePath; // Example CloudFront URL
            } else {
                $poster_image = $request->poster_image_url;
            }

            // Handle cover image file upload
            if ($request->hasFile('cover_image_file')) {
                $coverImageFile = $request->file('cover_image_file');
                $coverImagePath = $coverImageFile->store('img'); // Store in 'img' directory
                $cover_image = env('AWS_CLOUDFRONT_URL') . '/' . $coverImagePath; // Example CloudFront URL
            } else {
                $cover_image = $request->cover_image_url;
            }

            // Create the movie record
            $movie = Movie::create([
                'tv_show_id' => $request->tv_show_id,
                'title' => $request->title,
                'overview' => $request->overview,
                'run_time' => $request->run_time,
                'release_date' => $request->release_date,
                'poster_image' => $poster_image,
                'cover_image' => $cover_image,
                'total_raters' => $request->total_raters,
                'total_ratings' => $request->total_ratings,
                'average_rating' => $request->average_rating,
                'popularity' => $request->popularity,
                'terms_status' => $request->terms_status,
                'upload_status' => $request->upload_status,
                'last_upload_date' => $request->last_upload_date,
            ]);

            // Return a success response
            $res = new MovieResource($movie);
            return $this->sendSuccess(201, 'Movie created successfully', $res);
        } catch (\Exception $e) {
            // Handle any exceptions
            return $this->sendError(500, 'Failed to store movie');
        }
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
            'tv_show_id' => 'nullable|exists:tv_shows,id',
            'title' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'run_time' => 'nullable|integer',
            'release_date' => 'nullable|date',
            'poster_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust max size as per your requirements
            'poster_image_url' => 'nullable|url',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust max size as per your requirements
            'cover_image_url' => 'nullable|url',
            'total_raters' => 'nullable|integer',
            'total_ratings' => 'nullable|integer',
            'average_rating' => 'nullable|numeric|min:0|max:10',
            'popularity' => 'nullable|integer',
            'terms_status' => 'nullable|string|in:public,private',
            'upload_status' => 'nullable|string',
            'last_upload_date' => 'nullable|date',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Check user permissions
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        try {

            // Handle poster image file upload
            if ($request->hasFile('poster_image_file')) {
                $posterImageFile = $request->file('poster_image_file');
                $posterImagePath = $posterImageFile->store('img'); // Store in 'img' directory
                $poster_image = env('AWS_CLOUDFRONT_URL') . '/' . $posterImagePath; // Example CloudFront URL
                $movie->poster_image = $poster_image;
            } elseif ($request->has('poster_image_url')) {
                $movie->poster_image = $request->poster_image_url;
            }

            // Handle cover image file upload
            if ($request->hasFile('cover_image_file')) {
                $coverImageFile = $request->file('cover_image_file');
                $coverImagePath = $coverImageFile->store('img'); // Store in 'img' directory
                $cover_image = env('AWS_CLOUDFRONT_URL') . '/' . $coverImagePath; // Example CloudFront URL
                $movie->cover_image = $cover_image;
            } elseif ($request->has('cover_image_url')) {
                $movie->cover_image = $request->cover_image_url;
            }

            // Update other fields
            $movie->tv_show_id = $request->tv_show_id;
            $movie->title = $request->title;
            $movie->overview = $request->overview;
            $movie->run_time = $request->run_time;
            $movie->release_date = $request->release_date;
            $movie->total_raters = $request->total_raters;
            $movie->total_ratings = $request->total_ratings;
            $movie->average_rating = $request->average_rating;
            $movie->popularity = $request->popularity;
            $movie->terms_status = $request->terms_status;
            $movie->upload_status = $request->upload_status;
            $movie->last_upload_date = $request->last_upload_date;

            // Save the updated movie record
            $movie->save();

            // Return a success response
            $res = new MovieResource($movie);
            return $this->sendSuccess(200, 'Movie updated successfully', $res);
        } catch (\Exception $e) {
            // Handle any exceptions
            return $this->sendError(500, 'Failed to update movie');
        }
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

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $movie->delete();

        return $this->sendSuccess(2000, 'Movie deleted successfully');
    }

    //Lastest
    public function latest()
    {
        $latestMovies = Movie::orderBy('release_date', 'desc')->get();

        if ($latestMovies->isEmpty()) {
            return $this->sendError(404, 'No latest movies found');
        }

        $res = new MovieResourceCollection($latestMovies);
        return $this->sendSuccess(200, 'Latest movies found', $latestMovies);
    }

    //Popular
    public function popular()
    {
        $popularMovies = Movie::orderBy('popularity', 'desc')->get();

        if ($popularMovies->isEmpty()) {
            return $this->sendError(404, 'No popular movies found');
        }

        $res = new MovieResourceCollection($popularMovies);
        return $this->sendSuccess(200, 'Latest movies found', $res);
    }

    //Top Rate
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

    //TV-Show
    public function tvShow($tvShowId)
    {
        $movies = Movie::where('tv_show_id', $tvShowId)->get();

        if ($movies->isEmpty()) {
            return $this->sendError(404, 'TV Show genres not found');
        }

        $res = new MovieResourceCollection($movies);
        return $this->sendSuccess(200, 'TV Show Genres Found', $res);
    }

    //Year
    public function year($year)
    {
        // Fetch movies where the release_date year matches the input year
        $movies = Movie::whereYear('release_date', $year)->get();

        // Check if movies collection is empty
        if ($movies->isEmpty()) {
            return $this->sendError(404, 'Movies from this year not found');
        }

        // Create a resource collection of the found movies
        $res = new MovieResourceCollection($movies);

        // Return the success response
        return $this->sendSuccess(200, 'Movies from this year found', $res);
    }

    //Suggestions
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

    //Related
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

    //Search
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

    //Filter
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
