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
     /**
 * @OA\Get(
 *     path="/api/movie_countries",
 *     tags={"Movie_Countries"},
 *     summary="Get List movie_countries Data",
 *     description="enter your movie_countries here",
 *     operationId="movie_countries",
 *     @OA\Response(
 *         response="default",
 *         description="return array model movie_countries"
 *     )
 * )
 */
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
/**
 * @OA\Post(
 *     path="/api/movie_countries",
 *     tags={"Movie_Countries"},
 *     summary="movie_countries",
 *     description="movie_countries",
 *     operationId="Movie_Countries",
 *     @OA\RequestBody(
 *          required=true,
 *          description="form movie_countries",
 *          @OA\JsonContent(
 *            required={"movie_id", "country_id"},
 *              @OA\Property(property="movie_id", type="string"),
 *              @OA\Property(property="country_id", type="string"),
 *          ),
 *      ),
 *     @OA\Response(
 *         response="default",
 *         description=""
 *        
 *     )
 * )
 */
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
    /**
     * @OA\Get(
     *     path="/api/movie_countries/{id}",
     *     tags={"Movie_Countries"},
     *     summary="Detail",
     *     description="-",
     *     operationId="movie_countries/GetById",
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
    public function show($movieId)
    {
        $movieCountries = MovieCountry::where('movie_id', $movieId)->get();

        if (!$movieCountries) {
            return $this->sendError(404, 'Movie countries not found');
        }

        // Assuming you have a MovieCountryResourceCollection class to transform the data
        $res = new MovieCountryResourceCollection($movieCountries);

        return $this->sendSuccess(200, 'Movie Countries Found', $res);
    }
/**
     * @OA\Put(
     *     path="/api/movie_countries/{id}",
     *     tags={"Movie_Countries"},
     *     summary="Update movie_countries",
     *     description="-",
     *     operationId="movie_countries/update",
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
     *             required={"movie_id", "country_id"},
 *              @OA\Property(property="movie_id", type="string"),
 *              @OA\Property(property="country_id", type="string"),
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
/**
     * @OA\Delete(
     *     path="/api/movie_countries/{id}",
     *     tags={"Movie_Countries"},
     *     summary="Delete movie_countries",
     *     description="-",
     *     operationId="movie_countries/delete",
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
