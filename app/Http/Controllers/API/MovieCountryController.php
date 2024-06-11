<?php
//Api Done
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
    public function index()
    {
        // Retrieve all movie countries
        $movieCountries = MovieCountry::all();

        // Check if any movie countries are found
        if ($movieCountries->count() > 0) {
            // Return a success response with the transformed movie countries data
            $res = new MovieCountryResourceCollection($movieCountries);
            return $this->sendSuccess(200, 'Movie Countries Found', $res);
        } else {
            // Return an error response if no movie countries are found
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
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'country_code' => 'required|exists:countries,country_code',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Check if the current user is authorized to perform this action
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        // Create the movie country relationship using the request data
        $movieCountry = MovieCountry::create($request->all());

        // Return a success response with the created movie country relationship data
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

    public function show($id)
    {
        // Find the movie country relationship by its ID
        $movieCountry = MovieCountry::find($id);

        // Check if the movie country relationship is not found
        if (!$movieCountry) {
            return $this->sendError(404, 'Movie countries not found');
        }

        // Return a success response with the transformed movie country relationship data
        $res = new MovieCountryResource($movieCountry);
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
    public function update(Request $request, $id)
    {
        // Find the movie country relationship by its ID
        $movieCountry = MovieCountry::find($id);

        // Check if the movie country relationship is not found
        if (!$movieCountry) {
            return $this->sendError(404, 'Movie country not found');
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'country_code' => 'required|exists:countries,country_code',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Check if the current user is authorized to perform this action
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Update the movie country relationship with the incoming request data
        $movieCountry->update($request->all());

        // Return a success response with the updated movie country relationship data
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
    public function destroy($id)
    {
        // Find the movie country relationship by its ID
        $movieCountry = MovieCountry::find($id);

        // Check if the movie country relationship is not found
        if (!$movieCountry) {
            return $this->sendError(404, 'Movie country not found');
        }

        // Check if the current user is authorized to perform this action
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        // Delete the movie country relationship
        $movieCountry->delete();

        // Return a success response indicating that the movie country relationship was deleted successfully
        return $this->sendSuccess(200, 'Movie country deleted successfully');
    }

    public function countryCodeMovie($countryCode)
    {
        // Retrieve movies associated with the provided country code
        $movies = MovieCountry::where('country_code', $countryCode)->get();

        // Check if no movies are found for the provided country code
        if ($movies->isEmpty()) {
            return $this->sendError(404, 'Movies from this country not found');
        }

        // Return a success response with the movies from the country
        $res = new MovieCountryResourceCollection($movies);
        return $this->sendSuccess(200, 'Movies from this country found', $res);
    }


    public function movieIdCountry($movieId)
    {
        // Retrieve movies associated with the provided country code
        $movies = MovieCountry::where('movie_id', $movieId)->get();

        // Check if no movies are found for the provided country code
        if ($movies->isEmpty()) {
            return $this->sendError(404, 'Movies from this country not found');
        }

        // Return a success response with the movies from the country
        $res = new MovieCountryResourceCollection($movies);
        return $this->sendSuccess(200, 'Movies from this country found', $res);
    }
}
