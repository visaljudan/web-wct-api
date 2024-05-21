<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\RequestedMovie\RequestedMovieResource;
use App\Models\RequestedMovie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RequestedMovieController extends MainController
{
    // Index
    public function index()
    {
        if (!Gate::allows('admin_userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $requestedMovies = RequestedMovie::all();

        if ($requestedMovies->count() > 0) {
            $res = RequestedMovieResource::collection($requestedMovies);
            return $this->sendSuccess(200, 'Requested Movies Found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }

    // Store
    public function store(Request $request)
    {
        if (!Gate::allows('userRole', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'url' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $requestedMovie = RequestedMovie::create($request->all());

        $res = new RequestedMovieResource($requestedMovie);
        return $this->sendSuccess(201, 'Requested movie created successfully', $res);
    }

    // Show
    public function show($id)
    {
        if (!Gate::allows('admin_userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $requestedMovie = RequestedMovie::find($id);

        if (!$requestedMovie) {
            return $this->sendError(404, 'Requested movie not found');
        }

        $res = new RequestedMovieResource($requestedMovie);
        return $this->sendSuccess(200, 'Requested Movie found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        if (!Gate::allows('admin_userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $requestedMovie = RequestedMovie::find($id);

        if (!$requestedMovie) {
            return $this->sendError(404, 'Requested movie not found');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'url' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $requestedMovie->update($request->all());

        $res = new RequestedMovieResource($requestedMovie);
        return $this->sendSuccess(200, 'Requested movie updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        if (!Gate::allows('admin_userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $requestedMovie = RequestedMovie::find($id);

        if (!$requestedMovie) {
            return $this->sendError(404, 'Requested movie not found');
        }

        $requestedMovie->delete();
        return $this->sendSuccess(200, 'Requested movie deleted successfully');
    }
}
