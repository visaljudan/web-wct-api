<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\RequestedMovieResponse\RequestedMovieResponseResource;
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
    // Index
    public function index()
    {
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $requestedMovieResponses = RequestedMovieResponse::all();

        if ($requestedMovieResponses->count() > 0) {
            $res = RequestedMovieResponseResource::collection($requestedMovieResponses);
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
    // Store
    public function store(Request $request)
    {
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $validator = Validator::make($request->all(), [
            'requested_movie_id' => 'required|exists:requested_movies,id',
            'user_id' => 'required|exists:users,id',
            'response_message' => 'nullable|string',
            'response_status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $requestedMovieResponse = RequestedMovieResponse::create($request->all());

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
    // Show
    public function show($id)
    {
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $requestedMovieResponse = RequestedMovieResponse::find($id);

        if (!$requestedMovieResponse) {
            return $this->sendError(404, 'Requested movie response not found');
        }

        $res = new RequestedMovieResponseResource($requestedMovieResponse);
        return $this->sendSuccess(200, 'Requested Movie Response found', $res);
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
            'requested_movie_id' => 'exists:requested_movies,id',
            'user_id' => 'exists:users,id',
            'response_message' => 'nullable|string',
            'response_status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $requestedMovieResponse->update($request->all());

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

        $requestedMovieResponse->delete();
        return $this->sendSuccess(200, 'Requested movie response deleted successfully');
    }
}
