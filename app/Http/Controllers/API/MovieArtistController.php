<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MovieArtist\MovieArtistResource;
use App\Http\Resources\MovieArtist\MovieArtistResourceCollection;
use App\Models\MovieArtist;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MovieArtistController extends MainController
{
    // Index
    public function index()
    {
        $movieArtists = MovieArtist::all();

        if ($movieArtists->count() > 0) {
            $res = new MovieArtistResourceCollection($movieArtists);
            return $this->sendSuccess(200, 'Artists in Movies Found', $res);
        } else {
            return $this->sendError(404, 'No Records Found');
        }
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'artist_id' => 'required|exists:artists,id',
            'role_id' => 'required|exists:roles,id',
            'movie_artist_name' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieArtist = MovieArtist::create($request->all());

        $res = new MovieArtistResource($movieArtist);
        return $this->sendSuccess(201, 'Movie artist created successfully', $res);
    }

    // Show
    public function show($movieId)
    {
        $movieArtist = MovieArtist::where('movie_id', $movieId)->get();

        if (!$movieArtist) {
            return $this->sendError(404, 'Movie artist not found');
        }

        $res = new MovieArtistResourceCollection($movieArtist);
        return $this->sendSuccess(200, 'Movie Artist found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        $movieArtist = MovieArtist::find($id);

        if (!$movieArtist) {
            return $this->sendError(404, 'Movie artist not found');
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'artist_id' => 'required|exists:artists,id',
            'role_id' => 'required|exists:roles,id',
            'movie_artist_name' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieArtist->update($request->all());

        $res = new MovieArtistResource($movieArtist);
        return $this->sendSuccess(200, 'Movie artist updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        $movieArtist = MovieArtist::find($id);

        if (!$movieArtist) {
            return $this->sendError(404, 'Movie artist not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieArtist->delete();
        return $this->sendSuccess(200, 'Movie artist deleted successfully');
    }

    public function director($movieId)
    {
        // Fetch the role ID for the role with the name "Director"
        $directorRoleId = Role::where('role_name', 'Director')->value('id');

        // Fetch movie artists where role_id matches the director role ID
        $movieArtists = MovieArtist::where('movie_id', $movieId)
            ->where('role_id', $directorRoleId)
            ->get();

        if ($movieArtists->isEmpty()) {
            return $this->sendSuccess(404, 'Movie artists not found');
        }

        $res = new MovieArtistResourceCollection($movieArtists);
        return $this->sendSuccess(200, 'Movie Artists Found', $res);
    }
}
