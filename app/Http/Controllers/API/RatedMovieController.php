<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\RatedMovie\RatedMovieResource;
use App\Models\RatedMovie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RatedMovieController extends MainController
{
    // Index
    public function index()
    {
        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $ratedMovies = RatedMovie::all();

        if ($ratedMovies->count() > 0) {
            $res = RatedMovieResource::collection($ratedMovies);
            return $this->sendSuccess(200, 'Rated Movies Found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }

    // Store
    public function store(Request $request)
    {
        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,id',
            'rated_value' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $existingRatedValue = RatedMovie::where('user_id', $request->user_id)
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($existingRatedValue) {
            return $this->sendError(422, 'User has already rated this movie');
        }

        $ratedMovie = RatedMovie::create($request->all());

        $res = new RatedMovieResource($ratedMovie);
        return $this->sendSuccess(201, 'Rated movie created successfully', $res);
    }

    // Show
    public function show($id)
    {
        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $ratedMovie = RatedMovie::find($id);

        if (!$ratedMovie) {
            return $this->sendError(404, 'Rated movie not found');
        }

        $res = new RatedMovieResource($ratedMovie);
        return $this->sendSuccess(200, 'Rated Movie found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $ratedMovie = RatedMovie::find($id);

        if (!$ratedMovie) {
            return $this->sendError(404, 'Rated movie not found');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'movie_id' => 'exists:movies,id',
            'rated_value' => 'integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $ratedMovie->update($request->all());

        $res = new RatedMovieResource($ratedMovie);
        return $this->sendSuccess(200, 'Rated movie updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $ratedMovie = RatedMovie::find($id);

        if (!$ratedMovie) {
            return $this->sendError(404, 'Rated movie not found');
        }

        $ratedMovie->delete();
        return $this->sendSuccess(200, 'Rated movie deleted successfully');
    }
}
