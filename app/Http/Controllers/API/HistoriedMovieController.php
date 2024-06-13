<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\HistoriedMovie\HistoriedMovieResource;
use App\Http\Resources\HistoriedMovie\HistoriedMovieResourceCollection;
use App\Http\Resources\HistoriedMovieResourceCollection as ResourcesHistoriedMovieResourceCollection;
use App\Models\HistoriedMovie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoriedMovieController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/historied_movies",
     *     tags={"Historied-Movies"},
     *     summary="Get List Historied Movies",
     *     description="Get a list of historied movies for the authenticated user.",
     *     operationId="historied_movies",
     *     @OA\Response(response="200", description="Success"),
     *     security={{"Bearer":{}}}
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $historiedMovies = HistoriedMovie::where('user_id', $user->id)->get();

        if ($historiedMovies->isEmpty()) {
            return $this->sendError(404, 'No Historied Movies Found');
        }

        $res = new ResourcesHistoriedMovieResourceCollection($historiedMovies);

        return $this->sendSuccess(200, 'Historied Movies Found', $res);
    }

    /**
     * @OA\Post(
     *     path="/api/historied_movies",
     *     tags={"Historied-Movies"},
     *     summary="Save a Historied Movie",
     *     description="Save a movie as historied for the authenticated user.",
     *     operationId="save_historied_movie",
     *     @OA\RequestBody(
     *          required=true,
     *          description="Historied movie data",
     *          @OA\JsonContent(
     *            required={"user_id", "movie_id"},
     *              @OA\Property(property="user_id", type="string"),
     *              @OA\Property(property="movie_id", type="string"),
     *          ),
     *      ),
     *     @OA\Response(response="201", description="Success"),
     *     security={{"Bearer":{}}}
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $user = $request->user();

        // Check if the user has already marked this movie as historied
        $existingHistoriedMovie = HistoriedMovie::where('user_id', $user->id)
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($existingHistoriedMovie) {
            return $this->sendError(409, 'Movie already marked as historied by the user');
        }

        $historiedMovie = HistoriedMovie::create($request->all());

        return $this->sendSuccess(201, 'Movie marked as historied successfully', new HistoriedMovieResource($historiedMovie));
    }

    /**
     * @OA\Get(
     *     path="/api/historied_movies/{id}",
     *     tags={"Historied-Movies"},
     *     summary="Get a Historied Movie by ID",
     *     description="Get details of a specific historied movie for the authenticated user.",
     *     operationId="get_historied_movie_by_id",
     *     @OA\Parameter(
     *          name="id",
     *          description="Historied Movie ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"Bearer":{}}}
     * )
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $historiedMovie = HistoriedMovie::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$historiedMovie) {
            return $this->sendError(404, 'Historied movie not found');
        }

        return $this->sendSuccess(200, 'Historied Movie Found', new HistoriedMovieResource($historiedMovie));
    }

    /**
     * @OA\Put(
     *     path="/api/historied_movies/{id}",
     *     tags={"Historied-Movies"},
     *     summary="Update a Historied Movie",
     *     description="Update details of a specific historied movie for the authenticated user.",
     *     operationId="update_historied_movie",
     *     @OA\Parameter(
     *          name="id",
     *          description="Historied Movie ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Updated historied movie data",
     *          @OA\JsonContent(
     *             required={"user_id", "movie_id"},
     *              @OA\Property(property="user_id", type="string"),
     *              @OA\Property(property="movie_id", type="string"),
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"Bearer":{}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $user = $request->user();

        $historiedMovie = HistoriedMovie::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$historiedMovie) {
            return $this->sendError(404, 'Historied movie not found');
        }

        $historiedMovie->update($request->all());

        return $this->sendSuccess(200, 'Historied movie updated successfully', new HistoriedMovieResource($historiedMovie));
    }

    /**
     * @OA\Delete(
     *     path="/api/historied_movies/{id}",
     *     tags={"Historied-Movies"},
     *     summary="Delete a Historied Movie",
     *     description="Delete a specific historied movie for the authenticated user.",
     *     operationId="delete_historied_movie",
     *     @OA\Parameter(
     *          name="id",
     *          description="Historied Movie ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *    @OA\Response(response="200", description="Success"),
     *     security={{"Bearer":{}}}
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $historiedMovie = HistoriedMovie::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$historiedMovie) {
            return $this->sendError(404, 'Historied movie not found');
        }

        $historiedMovie->delete();

        return $this->sendSuccess(200, 'Historied movie deleted successfully');
    }
}
