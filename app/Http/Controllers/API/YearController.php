<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Year\YearResource;
use App\Http\Resources\Year\YearResourceCollection;
use App\Models\Year;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class YearController extends MainController
{
    // Index
    public function index()
    {
        $years = Year::all();

        if ($years->count() > 0) {
            $res = new YearResourceCollection($years);
            return $this->sendSuccess(200, 'Years found!', $res);
        } else {
            return $this->sendError('No Record Found');
        }
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|unique:years',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $year = Year::create([
            'year' => $request->year,
        ]);

        $res = new YearResource($year);
        return $this->sendSuccess(201, 'Year created successfully', $res);
    }

    // Show
    public function show($id)
    {
        // Find the year by ID
        $year = Year::find($id);

        // If year not found, return error response
        if (!$year) {
            return $this->sendError(404, 'Year not found');
        }

        // Return the year data
        $res = new YearResource($year);
        return $this->sendSuccess(200, 'Year found', $res);
    }

    // Update
    public function update(Request $request, $id)
    {
        $year = Year::find($id);

        if (!$year) {
            return $this->sendError(404, 'Year not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|unique:years,year,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $year->year = $request->year;
        $year->save();

        $res = new YearResource($year);
        return $this->sendSuccess(200, 'Year updated successfully', $res);
    }

    // Destroy
    public function destroy($id)
    {
        $year = Year::find($id);

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        if (!$year) {
            return $this->sendError(404, 'Year not found');
        }

        $year->delete();

        return $this->sendError(200, 'Year deleted successfully');
    }
}
