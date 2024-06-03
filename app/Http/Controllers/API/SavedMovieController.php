<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\SavedMovie\SavedMovieResource;
// use App\Http\Resources\SavedMovie\SavedMovieResourceCollection;
use App\Models\SavedMovie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SavedMovieController extends MainController
{
    // Index
    // public function index()
    // {
    //     if (!Gate::allows('userId', User::class)) {
    //         return $this->sendError(403, 'You are not allowed to perform this action');
    //     }

    //     $savedMovies = SavedMovie::all();

    //     if ($savedMovies->count() > 0) {
    //         $res = new SavedMovieResourceCollection($savedMovies);
    //         return $this->sendSuccess(200, 'Saved Movies Found', $res);
    //     } else {
    //         return $this->sendError(404, 'No Records Found');
    //     }
    // }
/**
 * @OA\Post(
 *     path="/api/saved-movies",
 *     tags={"Saved-Movies"},
 *     summary="saved-movies",
 *     description="saved-movies",
 *     operationId="Saved-Movies",
 *     @OA\RequestBody(
 *          required=true,
 *          description="form saved-movies",
 *          @OA\JsonContent(
 *            required={"user_id", "movie_id"},
 *              @OA\Property(property="user_id", type="string"),
 *              @OA\Property(property="movie_id", type="string"),
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
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $savedMovie = SavedMovie::create($request->all());

        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(201, 'Saved movie created successfully', $res);
    }
/**
     * @OA\Get(
     *     path="/api/saved-movies/{id}",
     *     tags={"Saved-Movies"},
     *     summary="Detail",
     *     description="-",
     *     operationId="saved-movies/GetById",
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

        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $savedMovie = SavedMovie::find($id);

        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(200, 'Saved Movie found', $res);
    }
/**
     * @OA\Put(
     *     path="/api/saved-movies/{id}",
     *     tags={"Saved-Movies"},
     *     summary="Update saved-movies",
     *     description="-",
     *     operationId="saved-movies/update",
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
     *             required={"user_id", "movie_id"},
 *              @OA\Property(property="user_id", type="string"),
 *              @OA\Property(property="movie_id", type="string"),
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
        $savedMovie = SavedMovie::find($id);

        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'movie_id' => 'exists:movies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $savedMovie->update($request->all());

        $res = new SavedMovieResource($savedMovie);
        return $this->sendSuccess(200, 'Saved movie updated successfully', $res);
    }
/**
     * @OA\Delete(
     *     path="/api/saved-movies/{id}",
     *     tags={"Saved-Movies"},
     *     summary="Delete saved-movies",
     *     description="-",
     *     operationId="saved-movies/delete",
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
        $savedMovie = SavedMovie::find($id);

        if (!$savedMovie) {
            return $this->sendError(404, 'Saved movie not found');
        }

        if (!Gate::allows('userId', User::class)) {
            return $this->sendError(403, 'You are not allowed to perform this action');
        }

        $savedMovie->delete();
        return $this->sendSuccess(200, 'Saved movie deleted successfully');
    }
}
