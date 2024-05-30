<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\TVShow\TVShowResource;
use App\Http\Resources\TVShow\TVShowResourceCollection;
use App\Models\TVShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TVShowController extends MainController
{
    // Index
    public function index()
    {
        $tvShows = TVShow::all();

        if ($tvShows->count() > 0) {
            $res = new TVShowResourceCollection($tvShows);
            return $this->sendSuccess(200, 'TV Shows Found', $res);
        } else {
            return $this->sendError(404, 'No Records Found');
        }
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tv_show_name' => 'required|string|unique:tv_shows',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $tvShow = TVShow::create($request->all());

        $res = new TVShowResource($tvShow);
        return $this->sendSuccess(201, 'TV show created successfully', $res);
    }

    // Show
    public function show($id)
    {
        $tvShow = TVShow::find($id);

        if (!$tvShow) {
            return $this->sendError(404, 'TV show not found');
        }

        $res = new TVShowResource($tvShow);
        return $this->sendSuccess(200, 'TV Show found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        $tvShow = TVShow::find($id);

        if (!$tvShow) {
            return $this->sendError(404, 'TV show not found');
        }

        $validator = Validator::make($request->all(), [
            'tv_show_name' => 'required|string|max:255|unique:tv_shows,tv_show_name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $tvShow->update($request->all());

        $res = new TVShowResource($tvShow);
        return $this->sendSuccess(200, 'TV show updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        $tvShow = TVShow::find($id);

        if (!$tvShow) {
            return $this->sendError(404, 'TV show not found');
        }

        $tvShow->delete();
        return $this->sendSuccess(200, 'TV show deleted successfully');
    }
}
