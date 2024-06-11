<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\RatedMovie\RatedMovieResource;
use App\Http\Resources\RatedMovie\RatedMovieResourceCollection;
use App\Models\Movie;
use App\Models\RatedMovie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RatedMovieController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/rated_movies",
     *     tags={"Rated-Movies"},
     *     summary="Get List rated-movies Data",
     *     description="enter your rated-movies here",
     *     operationId="rated_movies",
     *       @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */

    public function index(Request $request)
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

        // Check if the user is an admin
        if ($user->role === 'Admin') {
            // Return all rated movies
            $ratedMovies = RatedMovie::all();
        } else {
            // Return only rated movies associated with the user
            $ratedMovies = RatedMovie::where('user_id', $user->id)->get();
        }

        if ($ratedMovies->isEmpty()) {
            return $this->sendError(400, 'No Records Found');
        }

        // Return the rated movies
        $res = new RatedMovieResourceCollection($ratedMovies);
        return $this->sendSuccess(200, 'Rated Movies Found', $res);
    }

    /**
     * @OA\Post(
     *     path="/api/rated-movies",
     *     tags={"Rated-Movies"},
     *     summary="rated-movies",
     *     description="rated-movies",
     *     operationId="Rated-Movies",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form rated-movies",
     *          @OA\JsonContent(
     *            required={"user_id", "movie_id","rated_value"},
     *              @OA\Property(property="user_id", type="string"),
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="rated_value", type="integer"),
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *        
     *     )
     * )
     */

    public function storeUpdate(Request $request)
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

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'rated_value' => 'required|integer|between:0,5',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $existingRatedValue = RatedMovie::where('user_id', $user->id)
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($existingRatedValue) {
            // Update existing rating
            if ($request->rated_value == 0) {
                return $this->destroyRating($user, $existingRatedValue);
            }

            $existingRatedValue->rated_value = $request->rated_value;
            $existingRatedValue->save();
            $ratedMovie = $existingRatedValue;
        } else {

            $validator = Validator::make($request->all(), [
                'movie_id' => 'required|exists:movies,id',
                'rated_value' => 'required|integer|between:1,5',
            ]);

            if ($validator->fails()) {
                return $this->sendError(422, 'Validation failed', $validator->errors());
            }
            // Create a new rating
            $ratedMovie = RatedMovie::create([
                'user_id' => $user->id,
                'movie_id' => $request->movie_id,
                'rated_value' => $request->rated_value,
            ]);
        }

        // Recalculate total and average ratings
        $movie = Movie::find($request->movie_id);
        $totalRatings = RatedMovie::where('movie_id', $movie->id)->sum('rated_value');
        $ratingsCount = RatedMovie::where('movie_id', $movie->id)->count();
        $averageRating = $ratingsCount ? $totalRatings / $ratingsCount : 0;

        // Update movie ratings
        $movie->total_raters = $ratingsCount;
        $movie->total_ratings = $totalRatings;
        $movie->average_rating = $averageRating;
        $movie->save();

        // Return a success response
        $res = new RatedMovieResource($ratedMovie);
        return $this->sendSuccess(201, 'Rated movie created/updated successfully', $res);
    }

    /**
     * @OA\Get(
     *     path="/api/rated-movies/{id}",
     *     tags={"Rated-Movies"},
     *     summary="Detail",
     *     description="-",
     *     operationId="rated-movies/GetById",
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

    public function show(Request $request, $movieId)
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

        // Check if the authenticated user is admin
        if ($user->role === 'Admin') {
            // Admins can view all rated movies
            $ratedMovies = RatedMovie::where('movie_id', $movieId)->get();
            $res = new RatedMovieResourceCollection($ratedMovies);
        } else {
            // Regular users can only view their own rated movie for the specified movieId
            $ratedMovie = RatedMovie::where('movie_id', $movieId)
                ->where('user_id', $user->id)
                ->first();

            if (!$ratedMovie) {
                return $this->sendError(404, 'Rated movie not found');
            }

            $res = new RatedMovieResource($ratedMovie);
        }

        return $this->sendSuccess(200, 'Rated Movie found', $res);
    }

    /**
     * @OA\Delete(
     *     path="/api/rated-movies/{id}",
     *     tags={"Rated-Movies"},
     *     summary="Delete rated-movies",
     *     description="-",
     *     operationId="rated-movies/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */
    public function destroy(Request $request, $id)
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

        // Find the rated movie
        $ratedMovie = RatedMovie::find($id);

        if (!$ratedMovie) {
            return $this->sendError(404, 'Rated movie not found');
        }

        return $this->destroyRating($user, $ratedMovie);
    }


    private function destroyRating($user, $ratedMovie)
    {
        $movieId = $ratedMovie->movie_id;

        // Check if the authenticated user is admin or the owner of the rated movie
        if ($user->role !== 'Admin' && $user->id !== $ratedMovie->user_id) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $ratedMovie->delete();

        // Recalculate total and average ratings
        $movie = Movie::find($movieId);
        $totalRatings = RatedMovie::where('movie_id', $movie->id)->sum('rated_value');
        $ratingsCount = RatedMovie::where('movie_id', $movie->id)->count();
        $averageRating = $ratingsCount ? $totalRatings / $ratingsCount : 0;


        // Update movie ratings
        $movie->total_raters = $ratingsCount;
        $movie->total_ratings = $totalRatings;
        $movie->average_rating = $averageRating;
        $movie->save();

        return $this->sendSuccess(200, 'Rated movie deleted successfully');
    }
}
