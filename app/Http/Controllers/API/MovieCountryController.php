<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MovieCountry\MovieCountryResource;
use App\Http\Resources\MovieCountry\MovieCountryResourceCollection;
use App\Models\MovieCountry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MovieCountryController extends MainController
{
    // Index
    public function index()
    {
        $movieCountries = MovieCountry::all();

        if ($movieCountries->count() > 0) {
            $res = new MovieCountryResourceCollection($movieCountries);
            return $this->sendSuccess(200, 'Movie Countries Found', $res);
        } else {
            return $this->sendError(404, 'No Records Found');
        }
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'country_id' => 'required|exists:countries,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieCountry = MovieCountry::create($request->all());

        $res = new MovieCountryResource($movieCountry);
        return $this->sendSuccess(201, 'Movie country created successfully', $res);
    }

    // Show
    public function show($id)
    {
        $movieCountry = MovieCountry::find($id);

        if (!$movieCountry) {
            return $this->sendError(404, 'Movie country not found');
        }

        $res = new MovieCountryResource($movieCountry);
        return $this->sendSuccess(200, 'Movie Country found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        $movieCountry = MovieCountry::find($id);

        if (!$movieCountry) {
            return $this->sendError(404, 'Movie country not found');
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'country_id' => 'required|exists:countries,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieCountry->update($request->all());

        $res = new MovieCountryResource($movieCountry);
        return $this->sendSuccess(200, 'Movie country updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        $movieCountry = MovieCountry::find($id);

        if (!$movieCountry) {
            return $this->sendError(404, 'Movie country not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieCountry->delete();
        return $this->sendSuccess(200, 'Movie country deleted successfully');
    }
}
