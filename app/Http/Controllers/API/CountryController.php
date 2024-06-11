<?php
//Api Done
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
    /**
     * @OA\Get(
     *     path="/api/countries",
     *     tags={"Countries"},
     *     summary="Get List countries Data",
     *     description="enter your countries here",
     *     operationId="countries",
     *     @OA\Response(
     *         response="default",
     *         description="return array model countries"
     *     )
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/api/countries",
     *     tags={"Countries"},
     *     summary="countries",
     *     description="countries",
     *     operationId="Countries",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form countries",
     *          @OA\JsonContent(
     *            required={"country_code", "country_name"},
     *              @OA\Property(property="country_code", type="string"),
     *              @OA\Property(property="country_name", type="string"),
     *          ),
     *      ),
     *      @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *        
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string|unique:countries|max:2',
            'country_name' => 'required|string|unique:countries',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $country = Country::create($request->all());

        $res = new CountryResource($country);
        return $this->sendSuccess(201, 'Country created successfully', $res);
    }
    /**
     * @OA\Get(
     *     path="/api/countries/{id}",
     *     tags={"Countries"},
     *     summary="Detail",
     *     description="-",
     *     operationId="countries/GetById",
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
    public function show($countyCode)
    {
        $country = Country::where('country_code', $countyCode)->first();

        if (!$country) {
            return $this->sendError(404, 'Country not found');
        }

        $res = new CountryResource($country);
        return $this->sendSuccess(200, 'Country Found', $res);
    }
    /**
     * @OA\Put(
     *     path="/api/countries/{id}",
     *     tags={"Countries"},
     *     summary="Update countries",
     *     description="-",
     *     operationId="countries/update",
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
     *             required={"country_code", "country_name"},
     *              @OA\Property(property="country_code", type="string"),
     *              @OA\Property(property="country_name", type="string"),
     *          ),
     *      ),
     *      @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
    //Update
    public function update(Request $request, string $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return $this->sendError(404, 'Country not found');
        }

        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string|max:2|unique:countries,country_code,' . $id,
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
     * @OA\Delete(
     *     path="/api/countries/{id}",
     *     tags={"Countries"},
     *     summary="Delete countries",
     *     description="-",
     *     operationId="Countries/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
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
