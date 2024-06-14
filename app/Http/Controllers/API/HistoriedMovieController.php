<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\HistoriedMovie\HistoriedMovieResource;
use App\Http\Resources\HistoriedMovie\HistoriedMovieResourceCollection;
use App\Models\HistoriedMovie;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoriedMovieController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/historied_movies",
     *     tags={"Historied-Movies"},
     *     summary="Get List Artists Data",
     *     description="enter your historied_movies here",
     *     operationId="historied_movies",
     *     @OA\Response(response="200", description="Success"),
     *     security={{"Bearer":{}}}
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

        // Retrieve historied movies for the authenticated user
        $historiedMovies = HistoriedMovie::where('user_id', $user->id)->get();

        if ($historiedMovies->isEmpty()) {
            return $this->sendError(404, 'No Records Found');
        }

        $historiedMovies = HistoriedMovie::with('movie')->get();

        // Return a success response with the historied movies
        $res = new HistoriedMovieResourceCollection($historiedMovies);
        return $this->sendSuccess(200, 'Historied Movies Found', $res);
    }

    /**
     * @OA\Post(
     *     path="/api/historied_movies",
     *     tags={"Historied-Movies"},
     *     summary="historied-movies",
     *     description="historied-movies",
     *     operationId="Historied-Movies",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form historied-movies",
     *          @OA\JsonContent(
     *            required={"user_id", "movie_id"},
     *              @OA\Property(property="user_id", type="string"),
     *              @OA\Property(property="movie_id", type="string"),
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"Bearer":{}}}
     * )
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

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

        // Check if the authenticated user is the same as the user_id in the request
        if ($user->id !== $request->user_id) {
            return $this->sendError(403, 'You are not allowed');
        }

        // Check if the user has already historied this movie
        $existingHistoriedMovie = HistoriedMovie::where('user_id', $user->id)
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($existingHistoriedMovie) {
            return $this->sendError(409, 'Movie already historied by the user');
        }

        // Create a new historied movie
        $historiedMovie = HistoriedMovie::create($request->all());

        // Increment the popularity of the associated movie
        $movie = Movie::find($request->movie_id);
        if ($movie) {
            $movie->popularity += 1;
            $movie->save();
        }

        // Return a success response
        $res = new HistoriedMovieResource($historiedMovie);
        return $this->sendSuccess(201, 'Historied movie created successfully', $res);
    }



    /**
     * @OA\Get(
     *     path="/api/historied-movies/{id}",
     *     tags={"Historied-Movies"},
     *     summary="Detail",
     *     description="-",
     *     operationId="historied-movies/GetById",
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

        // Retrieve the specific historied movie for the authenticated user with the specified movie_id
        $historiedMovie = HistoriedMovie::where('movie_id', $movieId)
            ->where('user_id', $user->id)
            ->first();

        if (!$historiedMovie) {
            return $this->sendError(404, 'Historied movie not found');
        }

        $res = new HistoriedMovieResource($historiedMovie);
        return $this->sendSuccess(200, 'Historied Movie found', $res);
    }

    /**
     * @OA\Put(
     *     path="/api/historied_movies/{id}",
     *     tags={"Historied-Movies"},
     *     summary="Update historied-movies",
     *     description="-",
     *     operationId="historied-movies/update",
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
     *             required={"user_id", "movie_id"},
     *              @OA\Property(property="user_id", type="string"),
     *              @OA\Property(property="movie_id", type="string"),
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"Bearer":{}}}
     * )
     */
    public function update(Request $request, $movieId)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

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

        // Check if the authenticated user is the same as the user_id in the request
        if ($user->id !== $request->user_id) {
            return $this->sendError(403, 'You are not allowed');
        }

        $historiedMovie = HistoriedMovie::where('movie_id', $movieId)
            ->where('user_id', $user->id)
            ->first();

        if (!$historiedMovie) {
            return $this->sendError(404, 'Historied movie not found');
        }

        // Check if the authenticated user is the owner of the historied movie
        if ($user->id !== $historiedMovie->user_id) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Validate request data again for specific fields if needed
        $validator = Validator::make($request->all(), [
            // Add validation rules for specific fields if needed
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Update the historied movie
        $historiedMovie->update($request->all());

        // Return a success response
        $res = new HistoriedMovieResource($historiedMovie);
        return $this->sendSuccess(200, 'Historied movie updated successfully', $res);
    }

    /**
     * @OA\Delete(
     *     path="/api/historied_movies/{id}",
     *     tags={"Historied-Movies"},
     *     summary="Delete historied-movies",
     *     description="-",
     *     operationId="historied-movies/delete",
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
     *     security={{"Bearer":{}}}
     * )
     */

    public function destroy(Request $request, $movieId)
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

        // Find the historied movie by ID
        $historiedMovie = HistoriedMovie::where('movie_id', $movieId)
            ->where('user_id', $user->id)
            ->first();


        if (!$historiedMovie) {
            return $this->sendError(404, 'Historied movie not found');
        }

        // Check if the authenticated user is the owner of the historied movie
        if ($user->id !== $historiedMovie->user_id) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Delete the historied movie
        $historiedMovie->delete();

        // Return a success response
        return $this->sendSuccess(200, 'Historied movie deleted successfully');
    }
}
