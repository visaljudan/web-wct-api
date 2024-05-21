<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MovieGenre\MovieGenreResource;
use App\Http\Resources\MovieGenre\MovieGenreResourceCollection;
use App\Models\MovieGenre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MovieGenreController extends MainController
{
    //Index
    public function index()
    {
        $movieGenres = MovieGenre::all();

        if ($movieGenres->count() > 0) {
            $res = new MovieGenreResourceCollection($movieGenres);
            return $this->sendSuccess(200, 'Movie Gernes Found', $res);
        } else {
            return $this->sendError(400, 'No Recod Found');
        }
    }

    //Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'genre_id' => 'required|exists:genres,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $movieGenre = MovieGenre::create($request->all());

        $res = new MovieGenreResource($movieGenre);
        return $this->sendSuccess(201, 'Movie genre created successfully', $res);
    }

    //show
    public function show($id)
    {
        $movieGenre = MovieGenre::find($id);

        if (!$movieGenre) {
            return $this->sendSuccess(404, 'Movie genre not found');
        }

        $res = new MovieGenreResource($movieGenre);
        return $this->sendSuccess(200, 'Movie Gernes Found', $res);
    }

    //Update
    public function update(Request $request, $id)
    {
        $movieGenre = MovieGenre::find($id);

        if (!$movieGenre) {
            return $this->sendError(404, 'Movie genre not found');
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'genre_id' => 'required|exists:genres,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $movieGenre->update($request->all());

        $res = new MovieGenreResource($movieGenre);
        return $this->sendSuccess(200, 'Movie genre updated successfully', $res);
    }

    //Destroy
    public function destroy($id)
    {
        $movieGenre = MovieGenre::find($id);

        if (!$movieGenre) {
            return $this->sendError(404, 'Movie genre not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $movieGenre->delete();

        return $this->sendSuccess(200, 'Movie genre deleted successfully');
    }
}
