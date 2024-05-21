<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Artist\ArtistResource;
use App\Http\Resources\Artist\ArtistResourceCollection;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ArtistController extends MainController
{
    //Index
    public function index()
    {
        $artists = Artist::all();
        if ($artists->count() > 0) {
            $res = new ArtistResourceCollection($artists);
            return $this->sendSuccess(200, 'Artist Found', $res);
        } else {
            return $this->sendSuccess(400, 'No Record Found');
        }
    }

    //Stroe
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'artist_name' => 'required|string|unique:artists',
            'artist_profile' => 'required|string',
            // 'artist_profile' => 'nullable|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $artist = Artist::create([
            'artist_name' => $request->artist_name,
            'artist_profile' => $request->artist_profile,
        ]);

        $res = new ArtistResource($artist);
        return $this->sendSuccess(200, 'Artist created successfully', $res);
    }

    //Show
    public function show($id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return $this->sendError(404, 'Artist not found');
        }

        $res = new ArtistResource($artist);
        return $this->sendSuccess(200, 'Artist Found', $res);
    }

    //Update
    public function update(Request $request, $id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return $this->sendError(404, 'Artist not found');
        }

        $validator = Validator::make($request->all(), [
            'artist_name' => 'required|string|max:255|unique:artists,artist_name,' . $id,
            'artist_profile' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $artist->artist_name = $request->artist_name;
        $artist->artist_profile = $request->artist_profile;

        $artist->save();

        $res = new ArtistResource($artist);
        return $this->sendSuccess(200, 'Artist updated successfully', $res);
    }

    //Destroy
    public function destroy(string $id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return $this->sendError(404, 'Artist not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $artist->delete();
        return $this->sendSuccess(200, 'Artist deleted successfully');
    }
}
