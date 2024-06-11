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
    /**
     * @OA\Get(
     *     path="/api/years",
     *     tags={"Years"},
     *     summary="Get List years Data",
     *     description="enter your years here",
     *     operationId="years",
     *     @OA\Response(
     *         response="default",
     *         description="return array model years"
     *     )
     * )
     */
    // Index
    public function index()
    {
        $years = Year::orderBy('id', 'desc')->get();

            if ($years->count() > 0) {
            $res = new YearResourceCollection($years);
            return $this->sendSuccess(200, 'Years found!', $res);
        } else {
            return $this->sendError('No Record Found');
        }
    }
    /**
     * @OA\Post(
     *     path="/api/years",
     *     tags={"Years"},
     *     summary="years",
     *     description="years",
     *     operationId="Years",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form years",
     *          @OA\JsonContent(
     *            required={"year"},
     *              @OA\Property(property="year", type="integer"),
     *             
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *        
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/api/years/{id}",
     *     tags={"Years"},
     *     summary="Detail",
     *     description="-",
     *     operationId="years/GetById",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="return model admin"
     *     )
     * )
     */
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
    /**
     * @OA\Put(
     *     path="/api/years/{id}",
     *     tags={"Years"},
     *     summary="Update years",
     *     description="-",
     *     operationId="years/update",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="form admin",
     *          @OA\JsonContent(
     *             required={"year"},
     *              @OA\Property(property="year", type="integer"),
     *             
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
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
    /**
     * @OA\Delete(
     *     path="/api/years/{id}",
     *     tags={"Years"},
     *     summary="Delete years",
     *     description="-",
     *     operationId="years/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
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
