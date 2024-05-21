<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Language\LanguageResourceCollection;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class LanguageController extends MainController
{
    //Index
    public function index()
    {
        $languages = Language::all();

        if ($languages->count() > 0) {
            $res = new LanguageResourceCollection($languages);
            return $this->sendSuccess(200, 'Languages Foun', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }

    //Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_code' => 'required|string|unique:languages',
            'language_name' => 'required|string|unique:languages',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation faild', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $language = Language::create($request->all());

        $res = new LanguageResource($language);
        return $this->sendSuccess(201, 'Langueage created successfully', $res);
    }

    //Show
    public function show(string $id)
    {
        $language = Language::find($id);

        if (!$language) {
            return $this->sendError(404, 'Language not found');
        }

        $res = new LanguageResource($language);
        return $this->sendSuccess(200, 'Language Found', $res);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $language = Language::find($id);

        if (!$language) {
            return response()->json([
                'success' => false,
                'message' => 'Language not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'language_code' => 'required|string|unique:languages,language_code,' . $id,
            'language_name' => 'required|string|unique:languages,language_name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation fails', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $language->update($request->all());

        $res = new LanguageResource($language);
        return $this->sendSuccess(200, 'Language updated successfully', $res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $language = Language::find($id);

        if (!$language) {
            return $this->sendError(404, 'Language not found');
        }
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }
        $language->delete();

        return $this->sendSuccess(200, 'Language deleted successfully');
    }
}
