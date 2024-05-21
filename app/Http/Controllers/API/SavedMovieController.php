<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\SavedMovie\SavedMovieResource;
// use App\Http\Resources\SavedMovie\SavedMovieResourceCollection;
use App\Models\SavedMovie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SavedMovieController extends MainController
{
    // Index
    // public function index()
    // {
    //     if (!Gate::allows('userId', User::class)) {
    //         return $this->sendError(403, 'You are not allowed to perform this action');
    //     }

    //     $savedMovies = SavedMovie::all();

    //     if ($savedMovies->count() > 0) {
    //         $res = new SavedMovieResourceCollection($savedMovies);
    //         return $this->sendSuccess(200, 'Saved Movies Found', $res);
    //     } else {
    //         return $this->sendError(404, 'No Records Found');
    //     }
    // }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $savedMovie = SavedMovie::create($request->all());

        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(201, 'Saved movie created successfully', $res);
    }

    // Show
    public function show($id)
    {

        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $savedMovie = SavedMovie::find($id);

        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(200, 'Saved Movie found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        $savedMovie = SavedMovie::find($id);

        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'movie_id' => 'exists:movies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $savedMovie->update($request->all());

        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(200, 'Saved movie updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        $savedMovie = SavedMovie::find($id);

        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $savedMovie->delete();
        return $this->sendSuccess(200, 'Saved movie deleted successfully');
    }
}
