<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MovieLanguage\MovieLanguageResource;
use App\Http\Resources\MovieLanguage\MovieLanguageResourceCollection;
use App\Models\MovieLanguage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MovieLanguageController extends MainController
{
    // Index
    public function index()
    {
        $movieLanguages = MovieLanguage::all();

        if ($movieLanguages->count() > 0) {
            $res = new MovieLanguageResourceCollection($movieLanguages);
            return $this->sendSuccess(200, 'Movie Languages Found', $res);
        } else {
            return $this->sendError(404, 'No Records Found');
        }
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'language_code' => 'required|exists:languages,language_code',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieLanguage = MovieLanguage::create($request->all());

        $res = new MovieLanguageResource($movieLanguage);
        return $this->sendSuccess(201, 'Movie language created successfully', $res);
    }

    // Show
    public function show($id)
    {
        $movieLanguage = MovieLanguage::find($id);

        if (!$movieLanguage) {
            return $this->sendError(404, 'Movie language not found');
        }

        $res = new MovieLanguageResource($movieLanguage);
        return $this->sendSuccess(200, 'Movie Language found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        $movieLanguage = MovieLanguage::find($id);

        if (!$movieLanguage) {
            return $this->sendError(404, 'Movie language not found');
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'language_code' => 'required|exists:languages,language_code',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieLanguage->update($request->all());

        $res = new MovieLanguageResource($movieLanguage);
        return $this->sendSuccess(200, 'Movie language updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        $movieLanguage = MovieLanguage::find($id);

        if (!$movieLanguage) {
            return $this->sendError(404, 'Movie language not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $movieLanguage->delete();
        return $this->sendSuccess(200, 'Movie language deleted successfully');
    }
}
