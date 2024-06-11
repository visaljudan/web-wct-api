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
    /**
     * @OA\Get(
     *     path="/api/movie_languages",
     *     tags={"Movie_Languages"},
     *     summary="Get List Artists Data",
     *     description="enter your Artists here",
     *     operationId="movie_languages",
     *     @OA\Response(
     *         response="default",
     *         description="return array model Artists"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/movie_languages",
     *     tags={"Movie_Languages"},
     *     summary="movie_languages",
     *     description="movie_languages",
     *     operationId="Movie_Languages",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form movie_languages",
     *          @OA\JsonContent(
     *            required={"movie_id", "language_code"},
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="language_code", type="string"),
     *          ),
     *      ),
     *    @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
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
            return $this->sendError(403, 'You are not allowed');
        }

        $movieLanguage = MovieLanguage::create($request->all());

        $res = new MovieLanguageResource($movieLanguage);
        return $this->sendSuccess(201, 'Movie language created successfully', $res);
    }
    /**
     * @OA\Get(
     *     path="/api/movie_languages/{id}",
     *     tags={"Movie_Languages"},
     *     summary="Detail",
     *     description="-",
     *     operationId="movie_languages/GetById",
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
        $movieLanguage = MovieLanguage::find($id);

        if (!$movieLanguage) {
            return $this->sendError(404, 'Movie language not found');
        }

        $res = new MovieLanguageResource($movieLanguage);
        return $this->sendSuccess(200, 'Movie Language found', $res);
    }

    /**
     * @OA\Put(
     *     path="/api/movie_languages/{id}",
     *     tags={"Movie_Languages"},
     *     summary="Update movie_languages",
     *     description="-",
     *     operationId="movie_languages/update",
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
     *             required={"movie_id", "language_code"},
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="language_code", type="string"),
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/movie_languages/{id}",
     *     tags={"Movie_Languages"},
     *     summary="Delete movie_languages",
     *     description="-",
     *     operationId="movie_languages/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *      @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     *     )
     * )
     */
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

    public function languageCodeMovie($languageCode)
    {
        // Retrieve movies associated with the provided country code
        $movies = MovieLanguage::where('language_code', $languageCode)->get();

        // Check if no movies are found for the provided country code
        if ($movies->isEmpty()) {
            return $this->sendError(404, 'Movies from this language not found');
        }

        // Return a success response with the movies from the country
        $res = new MovieLanguageResourceCollection($movies);
        return $this->sendSuccess(200, 'Movies from this language found', $res);
    }


    public function movieIdLanguage($movieId)
    {
        // Retrieve movies associated with the provided country code
        $movies = MovieLanguage::where('movie_id', $movieId)->get();

        // Check if no movies are found for the provided country code
        if ($movies->isEmpty()) {
            return $this->sendError(404, 'Movies from this language not found');
        }

        // Return a success response with the movies from the country
        $res = new MovieLanguageResourceCollection($movies);
        return $this->sendSuccess(200, 'Movies from this language found', $res);
    }
}
