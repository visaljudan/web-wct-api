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
