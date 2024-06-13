<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\SavedMovie\SavedMovieResource;
use App\Http\Resources\SavedMovie\SavedMovieResourceCollection;
use App\Models\SavedMovie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SavedMovieController extends MainController
{
     /**
     * @OA\Get(
     *     path="/api/saved_movies",
     *     tags={"Saved-Movies"},
     *     summary="Get List Artists Data",
     *     description="enter your saved_movies here",
     *     operationId="saved_movies",
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

        // Retrieve saved movies for the authenticated user
        $savedMovies = SavedMovie::where('user_id', $user->id)->get();

        if ($savedMovies->isEmpty()) {
            return $this->sendError(404, 'No Records Found');
        }

        $savedMovies = SavedMovie::with('movie')->get();

        // Return a success response with the saved movies
        $res = new SavedMovieResourceCollection($savedMovies);
        return $this->sendSuccess(200, 'Saved Movies Found', $res);
    }

    /**
     * @OA\Post(
     *     path="/api/saved_movies",
     *     tags={"Saved-Movies"},
     *     summary="saved-movies",
     *     description="saved-movies",
     *     operationId="Saved-Movies",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form saved-movies",
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

        // Check if the user has already saved this movie
        $existingSavedMovie = SavedMovie::where('user_id', $user->id)
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($existingSavedMovie) {
            return $this->sendError(409, 'Movie already saved by the user');
        }

        // Create a new saved movie
        $savedMovie = SavedMovie::create($request->all());

        // Return a success response
        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(201, 'Saved movie created successfully', $res);
    }


    /**
     * @OA\Get(
     *     path="/api/saved-movies/{id}",
     *     tags={"Saved-Movies"},
     *     summary="Detail",
     *     description="-",
     *     operationId="saved-movies/GetById",
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

        // Retrieve the specific saved movie for the authenticated user with the specified movie_id
        $savedMovie = SavedMovie::where('movie_id', $movieId)
            ->where('user_id', $user->id)
            ->first();

        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(200, 'Saved Movie found', $res);
    }

    /**
     * @OA\Put(
     *     path="/api/saved_movies/{id}",
     *     tags={"Saved-Movies"},
     *     summary="Update saved-movies",
     *     description="-",
     *     operationId="saved-movies/update",
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

        $savedMovie = SavedMovie::where('movie_id', $movieId)
            ->where('user_id', $user->id)
            ->first();

        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        // Check if the authenticated user is the owner of the saved movie
        if ($user->id !== $savedMovie->user_id) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Validate request data again for specific fields if needed
        $validator = Validator::make($request->all(), [
            // Add validation rules for specific fields if needed
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Update the saved movie
        $savedMovie->update($request->all());

        // Return a success response
        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(200, 'Saved movie updated successfully', $res);
    }

    /**
     * @OA\Delete(
     *     path="/api/saved_movies/{id}",
     *     tags={"Saved-Movies"},
     *     summary="Delete saved-movies",
     *     description="-",
     *     operationId="saved-movies/delete",
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

        // Find the saved movie by ID
        $savedMovie = SavedMovie::where('movie_id', $movieId)
            ->where('user_id', $user->id)
            ->first();


        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        // Check if the authenticated user is the owner of the saved movie
        if ($user->id !== $savedMovie->user_id) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Delete the saved movie
        $savedMovie->delete();
        
        // Return a success response
        return $this->sendSuccess(200, 'Saved movie deleted successfully');
    }
}
