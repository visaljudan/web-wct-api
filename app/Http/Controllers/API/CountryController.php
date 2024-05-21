<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Country\CountryResourceCollection;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CountryController extends MainController
{
    //Index
    public function index()
    {
        $countries = Country::all();

        if ($countries->count() > 0) {
            $res =  new CountryResourceCollection($countries);
            return $this->sendSuccess(200, 'Country Found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }

    //Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string|unique:countries',
            'country_name' => 'required|string|unique:countries',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $country = Country::create($request->all());

        $res = new CountryResource($country);
        return $this->sendSuccess(201, 'Country created successfully', $res);
    }

    //Show
    public function show(string $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return $this->sendError(404, 'Country not found');
        }

        $res = new CountryResource($country);
        return $this->sendSuccess(200, 'Country Found', $country);
    }

    //Update
    public function update(Request $request, string $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return $this->sendError(404, 'Country not found');
        }

        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string|unique:countries,country_code,' . $id,
            'country_name' => 'required|string|unique:countries,country_name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $country->update($request->all());

        $res = new CountryResource($country);
        return $this->sendSuccess(200, 'Country updated successfully', $res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return $this->sendError(404, 'Country not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }
        $country->delete();

        return $this->sendSuccess(200, 'Country deleted successfully');
    }
}
