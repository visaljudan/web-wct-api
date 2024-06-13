<?php
//Api done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\RequestedMovieResponse\RequestedMovieResponseResource;
use App\Http\Resources\RequestedMovieResponse\RequestedMovieResponseResourceCollection;
use App\Models\RequestedMovie;
use App\Models\RequestedMovieResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RequestedMovieResponseController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/requested-movie-responses",
     *     tags={"Requested-Movie-Responses"},
     *     summary="Get List requested-movie-responses Data",
     *     description="enter your requested-movie-responses here",
     *     operationId="requested-movie-responses",
     *     @OA\Response(
     *         response="default",
     *         description="return array model requested-movie-responses"
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
            $requestedMovieResponses = RequestedMovieResponse::all();
        } else {
            $requestedMovieResponses = RequestedMovieResponse::where('user_id', $user->id)->get();
        }

        if ($requestedMovieResponses->count() > 0) {
            $res = new RequestedMovieResponseResourceCollection($requestedMovieResponses);
            return $this->sendSuccess(200, 'Requested Movie Responses Found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/requested-movie-responses",
     *     tags={"Requested-Movie-Responses"},
     *     summary="requested-movie-responses",
     *     description="requested-movie-responses",
     *     operationId="Requested-Movie-Responses",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form requested-movie-responses",
     *          @OA\JsonContent(
     *            required={"requested_movie_id", "user_id", "response_message","response_status"},
     *              @OA\Property(property="requested_movie_id", type="string"),
     *              @OA\Property(property="user_id", type="string"),
     * @OA\Property(property="response_message", type="string"),
     * @OA\Property(property="response_status", type="string"),
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
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $validator = Validator::make($request->all(), [
            'requested_movie_id' => 'required|exists:requested_movies,id',
            'response_message' => 'nullable|string',
            'response_status' => 'nullable|string|in:reject,accept,waiting',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Find the requested movie
        $requestedMovie = RequestedMovie::find($request->requested_movie_id);

        if (!$requestedMovie) {
            return $this->sendError(404, 'Requested movie not found');
        }

        // Create the requested movie response
        $requestedMovieResponse = RequestedMovieResponse::create([
            'user_id' => $requestedMovie->user_id, // Use the user_id from the requested movie
            'requested_movie_id' => $request->requested_movie_id,
            'response_message' => $request->response_message,
            'response_status' => $request->response_status,
        ]);

        // Update the status of the requested movie
        $requestedMovie->status = $request->response_status;
        $requestedMovie->save();

        $res = new RequestedMovieResponseResource($requestedMovieResponse);
        return $this->sendSuccess(201, 'Requested movie response created successfully', $res);
    }

    /**
     * @OA\Get(
     *     path="/api/requested-movie-responses/{id}",
     *     tags={"Requested-Movie-Responses"},
     *     summary="Detail",
     *     description="-",
     *     operationId="requested-movie-responses/GetById",
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

        // Find the requested movie response
        $requestedMovieResponse = RequestedMovieResponse::find($id);

        if (!$requestedMovieResponse) {
            return $this->sendError(404, 'Requested movie response not found');
        }

        // Check if the user is either an admin or the user associated with the requested movie response
        if ($user->role === 'Admin' || $requestedMovieResponse->user_id === $user->id) {
            $res = new RequestedMovieResponseResource($requestedMovieResponse);
            return $this->sendSuccess(200, 'Requested Movie Response found', $res);
        } else {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }
    }

    /**
     * @OA\Put(
     *     path="/api/requested-movie-responses/{id}",
     *     tags={"Requested-Movie-Responses"},
     *     summary="Update requested-movie-responses",
     *     description="-",
     *     operationId="requested-movie-responses/update",
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
     *             required={"requested_movie_id", "user_id","response_message","response_status"},
     *              @OA\Property(property="requested_movie_id", type="string"),
     *              @OA\Property(property="user_id", type="string"),
     * @OA\Property(property="response_message", type="string"),
     * @OA\Property(property="response_status", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    // Update
    public function update(Request $request, $id)
    {
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $requestedMovieResponse = RequestedMovieResponse::find($id);

        if (!$requestedMovieResponse) {
            return $this->sendError(404, 'Requested movie response not found');
        }

        $validator = Validator::make($request->all(), [
            'response_message' => 'nullable|string',
            'response_status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $requestedMovieResponse->update($request->all());
        // Update the associated RequestedMovie's status
        $requestedMovie = RequestedMovie::find($requestedMovieResponse->requested_movie_id);
        $requestedMovie->status = $request->input('response_status');
        $requestedMovie->save();

        $res = new RequestedMovieResponseResource($requestedMovieResponse);
        return $this->sendSuccess(200, 'Requested movie response updated successfully', $res);
    }
    /**
     * @OA\Delete(
     *     path="/api/requested-movie-responses/{id}",
     *     tags={"Requested-Movie-Responses"},
     *     summary="Delete requested-movie-responses",
     *     description="-",
     *     operationId="requested-movie-responses/delete",
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
    // Destroy
    public function destroy($id)
    {
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $requestedMovieResponse = RequestedMovieResponse::find($id);

        if (!$requestedMovieResponse) {
            return $this->sendError(404, 'Requested movie response not found');
        }

        $requestedMovieId = $requestedMovieResponse->requested_movie_id;

        $requestedMovieResponse->delete();

        // Update the status of the associated RequestedMovie
        $requestedMovie = RequestedMovie::find($requestedMovieId);
        if ($requestedMovie) {
            $requestedMovie->status = 'waiting';
            $requestedMovie->save();
        }

        return $this->sendSuccess(200, 'Requested movie response deleted successfully');
    }
}
