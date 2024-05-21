<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Genre\GenreResource;
use App\Http\Resources\Genre\GenreResourceCollection;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class GenreController extends MainController
{
    //Index
    public function index()
    {
        $genres = Genre::all();

        if ($genres->count() > 0) {

            $res = new GenreResourceCollection($genres);
            return $this->sendSuccess(200, 'Genres found!', $res);
        } else {
            return $this->sendError('No Record Found');
        }
    }

    //Store
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'genre_name' => 'required|string|unique:genres',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'No Record Found', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $genre = Genre::create([
            'genre_name' => $request->genre_name,
        ]);

        $res = new GenreResource($genre);
        return $this->sendSuccess(201, 'Genre created successfully', $res);
    }

    //Show
    public function show($id)
    {
        // Find the genre by ID
        $genre = Genre::find($id);

        // If genre not found, return error response
        if (!$genre) {
            return $this->sendError(404, 'Genre not found');
        }

        // Return the genre data
        $res = new GenreResource($genre);
        return $this->sendSuccess(200, 'Genre found', $res);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return $this->sendError(404, 'Genre not found');
        }


        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $validator = Validator::make($request->all(), [
            'genre_name' => 'required|string|max:255|unique:genres,genre_name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $genre->genre_name = $request->genre_name;
        $genre->save();

        $res = new GenreResource($genre);
        return $this->sendSuccess(200, 'Genre updated successfully', $res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        if (!$genre) {
            return $this->sendError(404, 'Genre not found', !Gate::allows('admin'));
        }

        $genre->delete();

        return $this->sendError(200, 'Genre deleted successfully');
    }
}
