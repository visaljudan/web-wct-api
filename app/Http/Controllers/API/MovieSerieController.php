<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MovieSerie\MovieSerieResource;
use App\Http\Resources\MovieSerie\MovieSerieResourceCollection;
use App\Models\MovieSerie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MovieSerieController extends MainController
{
    // Index
    public function index()
    {
        $movieSeries = MovieSerie::all();

        if ($movieSeries->count() > 0) {
            $res = new MovieSerieResourceCollection($movieSeries);
            return $this->sendSuccess(200, 'Movie Series Found', $res);
        } else {
            return $this->sendError(404, 'No Records Found');
        }
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'season_number' => 'required|integer|min:1',
            'episode_number' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Check if a movie series with the same movie_id, season_number, and episode_number already exists
        $existingMovieSeries = MovieSerie::where('movie_id', $request->movie_id)
            ->where('season_number', $request->season_number)
            ->where('episode_number', $request->episode_number)
            ->first();

        if ($existingMovieSeries) {
            return $this->sendError(422, 'Movie series with the same movie_id, season_number, and episode_number already exists');
        }

        $movieSeries = MovieSerie::create($request->all());

        $res = new MovieSerieResource($movieSeries);
        return $this->sendSuccess(201, 'Movie series created successfully', $res);
    }

    // Show
    public function show($id)
    {
        $movieSeries = MovieSerie::find($id);

        if (!$movieSeries) {
            return $this->sendError(404, 'Movie series not found');
        }

        $res = new MovieSerieResource($movieSeries);
        return $this->sendSuccess(200, 'Movie Series found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        $movieSeries = MovieSerie::find($id);

        if (!$movieSeries) {
            return $this->sendError(404, 'Movie series not found');
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'exists:movies,id',
            'season_number' => 'integer|min:1',
            'episode_number' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Check if the updated movie series conflicts with existing entries
        $existingMovieSeries = MovieSerie::where('movie_id', $request->movie_id ?? $movieSeries->movie_id)
            ->where('season_number', $request->season_number ?? $movieSeries->season_number)
            ->where('episode_number', $request->episode_number ?? $movieSeries->episode_number)
            ->where('id', '!=', $id)
            ->first();

        if ($existingMovieSeries) {
            return $this->sendError(422, 'Updated movie series conflicts with existing entries');
        }

        $movieSeries->update($request->all());

        $res = new MovieSerieResource($movieSeries);
        return $this->sendSuccess(200, 'Movie series updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        $movieSeries = MovieSerie::find($id);

        if (!$movieSeries) {
            return $this->sendError(404, 'Movie series not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieSeries->delete();
        return $this->sendSuccess(200, 'Movie series deleted successfully');
    }
}
