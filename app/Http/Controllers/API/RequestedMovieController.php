<?php
//Api done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\RequestedMovie\RequestedMovieResource;
use App\Http\Resources\RequestedMovie\RequestedMovieResourceCollection;
use App\Models\RequestedMovie;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RequestedMovieController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/requested-movies",
     *     tags={"Requested-Movies"},
     *     summary="Get List requested-movies Data",
     *     description="enter your requested-movies here",
     *     operationId="requested-movies",
     *     @OA\Response(
     *         response="default",
     *         description="return array model requested-movies"
     *     )
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
            // Return all requested movies
            $requestedMovies = RequestedMovie::all();
            $res = new RequestedMovieResourceCollection($requestedMovies);
        } else {
            // Return only requested movies associated with the user's ID
            $requestedMovies = RequestedMovie::where('user_id', $user->id)->get();
            $res = new RequestedMovieResource($requestedMovies);
        }

        if ($requestedMovies->isEmpty()) {
            return $this->sendError(400, 'No Record Found');
        }

        // Return the requested movies

        return $this->sendSuccess(200, 'Requested Movies Found', $res);
    }

    /**
     * @OA\Post(
     *     path="/api/requested-movies",
     *     tags={"Requested-Movies"},
     *     summary="requested-movies",
     *     description="requested-movies",
     *     operationId="Requested-Movies",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form requested-movies",
     *          @OA\JsonContent(
     *            required={"user_id", "title", "description", "image_path", "status"},
     *              @OA\Property(property="user_id", type="string"),
     *              @OA\Property(property="artist_profile", type="string"),
     *  @OA\Property(property="description", type="string"),
     *  @OA\Property(property="image_path", type="string"),
     *  @OA\Property(property="status", type="string"),
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

        // Check if the user's role is "User Subscription"
        if ($user->role !== 'User Subscription') {
            return $this->sendError(403, 'You are not allowed');
        }

        // Check if the user has subscription plan ID 3
        $subscription = UserSubscription::where('user_id', $user->id)
            ->where('subscription_plan_id', 3)
            ->first();

        if (!$subscription) {
            return $this->sendError(403, "You are not allowed! It's allowed only for subscription plan 3");
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Create the requested movie with the authenticated user's ID
        $requestedMovie = RequestedMovie::create([
            'user_id' => $user->id,
            'title' => $request->input('title', ''),
            'description' => $request->input('description', ''),
            'url' => $request->input('url', ''),
            'status' => 'pending',
        ]);

        $res = new RequestedMovieResource($requestedMovie);
        return $this->sendSuccess(201, 'Requested movie created successfully', $res);
    }

    /**
     * @OA\Get(
     *     path="/api/requested-movies/{id}",
     *     tags={"Requested-Movies"},
     *     summary="Detail",
     *     description="-",
     *     operationId="requested-movies/GetById",
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
        // Get the token from the request header
        $token = request()->header('Authorization');

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

        // Retrieve the requested movie
        $requestedMovie = RequestedMovie::find($id);

        if (!$requestedMovie) {
            return $this->sendError(404, 'Requested movie not found');
        }

        // Check if the user is an admin or the owner of the requested movie
        if ($user->role !== 'Admin' && $requestedMovie->user_id !== $user->id) {
            return $this->sendError(403, "You are not allowed. It's not with you ID");
        }

        // Return the requested movie
        $res = new RequestedMovieResource($requestedMovie);
        return $this->sendSuccess(200, 'Requested Movie found', $res);
    }

    /**
     * @OA\Put(
     *     path="/api/requested-movies/{id}",
     *     tags={"Requested-Movies"},
     *     summary="Update requested-movies",
     *     description="-",
     *     operationId="requested-movies/update",
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
     *             required={"user_id", "title", "description", "image_path", "status"},
     *              @OA\Property(property="user_id", type="string"),
     *              @OA\Property(property="artist_profile", type="string"),
     *  @OA\Property(property="description", type="string"),
     *  @OA\Property(property="image_path", type="string"),
     *  @OA\Property(property="status", type="string"),
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

        // Retrieve the requested movie
        $requestedMovie = RequestedMovie::find($id);

        if (!$requestedMovie) {
            return $this->sendError(404, 'Requested movie not found');
        }

        // Check if the user is an admin or the owner of the requested movie
        if ($requestedMovie->user_id !== $user->id) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Update the requested movie
        $requestedMovie->update($request->all());

        $res = new RequestedMovieResource($requestedMovie);
        return $this->sendSuccess(200, 'Requested movie updated successfully', $res);
    }

    /**
     * @OA\Delete(
     *     path="/api/requested-movies/{id}",
     *     tags={"Requested-Movies"},
     *     summary="Delete requested-movies",
     *     description="-",
     *     operationId="requested-movies/delete",
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
        // Get the token from the request header
        $token = request()->header('Authorization');

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

        // Retrieve the requested movie
        $requestedMovie = RequestedMovie::find($id);

        if (!$requestedMovie) {
            return $this->sendError(404, 'Requested movie not found');
        }

        // Check if the user is an admin or the owner of the requested movie
        if ($user->role !== 'Admin' && $requestedMovie->user_id !== $user->id) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Delete the requested movie
        $requestedMovie->delete();

        return $this->sendSuccess(200, 'Requested movie deleted successfully');
    }
}
