<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MovieGenre\MovieGenreResource;
use App\Http\Resources\MovieGenre\MovieGenreResourceCollection;
use App\Models\MovieGenre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MovieGenreController extends MainController
{
     /**
 * @OA\Get(
 *     path="/api/movie_genres",
 *     tags={"Movie_Genres"},
 *     summary="Get List movie_genres Data",
 *     description="enter your movie_genres here",
 *     operationId="movie_genres",
 *     @OA\Response(
 *         response="default",
 *         description="return array model movie_genres"
 *     )
 * )
 */
    //Index
    public function index()
    {
        $movieGenres = MovieGenre::all();

        if ($movieGenres->count() > 0) {
            $res = new MovieGenreResourceCollection($movieGenres);
            return $this->sendSuccess(200, 'Movie Gernes Found', $res);
        } else {
            return $this->sendError(400, 'No Recod Found');
        }
    }
/**
 * @OA\Post(
 *     path="/api/movie_genres",
 *     tags={"Movie_Genres"},
 *     summary="armovie_genrestists",
 *     description="movie_genres",
 *     operationId="Movie_Genres",
 *     @OA\RequestBody(
 *          required=true,
 *          description="form movie_genres",
 *          @OA\JsonContent(
 *            required={"movie_id", "genre_id"},
 *              @OA\Property(property="movie_id", type="string"),
 *              @OA\Property(property="genre_id", type="string"),
 *          ),
 *      ),
 *     @OA\Response(
 *         response="default",
 *         description=""
 *        
 *     )
 * )
 */
    //Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'genre_id' => 'required|exists:genres,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $movieGenre = MovieGenre::create($request->all());

        $res = new MovieGenreResource($movieGenre);
        return $this->sendSuccess(201, 'Movie genre created successfully', $res);
    }
/**
     * @OA\Get(
     *     path="/api/movie_genres/{id}",
     *     tags={"Movie_Genres"},
     *     summary="Detail",
     *     description="-",
     *     operationId="movie_genres/GetById",
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
    //show
    public function show($movieId)
    {
        $movieGenres = MovieGenre::where('movie_id', $movieId)->get();

        if ($movieGenres->isEmpty()) {
            return $this->sendSuccess(404, 'Movie genres not found');
        }

        $res = new MovieGenreResourceCollection($movieGenres);
        return $this->sendSuccess(200, 'Movie Genres Found', $res);
    }

/**
     * @OA\Put(
     *     path="/api/movie_genres/{id}",
     *     tags={"Movie_Genres"},
     *     summary="Update movie_genres",
     *     description="-",
     *     operationId="movie_genres/update",
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
     *             required={"movie_id", "genre_id"},
 *              @OA\Property(property="movie_id", type="string"),
 *              @OA\Property(property="genre_id", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    //Update
    public function update(Request $request, $id)
    {
        $movieGenre = MovieGenre::find($id);

        if (!$movieGenre) {
            return $this->sendError(404, 'Movie genre not found');
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'genre_id' => 'required|exists:genres,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $movieGenre->update($request->all());

        $res = new MovieGenreResource($movieGenre);
        return $this->sendSuccess(200, 'Movie genre updated successfully', $res);
    }
/**
     * @OA\Delete(
     *     path="/api/movie_genres/{id}",
     *     tags={"Movie_Genres"},
     *     summary="Delete movie_genres",
     *     description="-",
     *     operationId="movie_genres/delete",
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
    //Destroy
    public function destroy($id)
    {
        $movieGenre = MovieGenre::find($id);

        if (!$movieGenre) {
            return $this->sendError(404, 'Movie genre not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allows');
        }

        $movieGenre->delete();

        return $this->sendSuccess(200, 'Movie genre deleted successfully');
    }

    //Index movie by genre id
    public function movies($genreId)
    {
        $movie = MovieGenre::where('genre_id', $genreId)->get();

        if (!$movie) {
            return $this->sendSuccess(404, 'Movie genres not found');
        }

        $movies = $movie->pluck('movie');
        $additionalData = $movies->pluck('title');



        $res = new MovieGenreResourceCollection($movie,  $additionalData);
        return $this->sendSuccess(200, 'Movie Genres Found', $res);
    }
}
