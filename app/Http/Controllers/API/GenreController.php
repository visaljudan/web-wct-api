<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Genre\GenreResource;
use App\Http\Resources\Genre\GenreResourceCollection;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class GenreController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/genres",
     *     tags={"Genres"},
     *     summary="Get List genres Data",
     *     description="enter your genres here",
     *     operationId="genres",
     *     @OA\Response(
     *         response="default",
     *         description="return array model genres"
     *     )
     * )
     */
    public function index()
    {
        $genres = Genre::all();

        if ($genres->count() > 0) {
            $res = new GenreResourceCollection($genres);
            return $this->sendSuccess(200, 'Genres found!', $res);
        } else {
            return $this->sendError('No Record Found');
        }
    }
    /**
     * @OA\Post(
     *     path="/api/genres",
     *     tags={"Genres"},
     *     summary="genres",
     *     description="genres",
     *     operationId="Genres",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form genres",
     *          @OA\JsonContent(
     *            required={"genre_name"},
     *              @OA\Property(property="genre_name", type="string"),
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
            'genre_name' => 'required|string|unique:genres',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $genre = Genre::create($request->all());

        $res = new GenreResource($genre);
        return $this->sendSuccess(201, 'Genre created successfully', $res);
    }
    /**
     * @OA\Get(
     *     path="/api/genres/{id}",
     *     tags={"Genres"},
     *     summary="Detail",
     *     description="-",
     *     operationId="genres/GetById",
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
        $genre = Genre::find($id);

        if (!$genre) {
            return $this->sendError(404, 'Genre not found');
        }

        $res = new GenreResource($genre);
        return $this->sendSuccess(200, 'Genre found', $res);
    }
    /**
     * @OA\Put(
     *     path="/api/genres/{id}",
     *     tags={"Genres"},
     *     summary="Update genres",
     *     description="-",
     *     operationId="genres/update",
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
     *             required={"genre_name"},
     *              @OA\Property(property="genre_name", type="string"),
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
        $genre = Genre::find($id);

        if (!$genre) {
            return $this->sendError(404, 'Genre not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $validator = Validator::make($request->all(), [
            'genre_name' => 'required|string|max:255|unique:genres,genre_name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $genre->genre_name = $request->genre_name;
        $genre->save();

        $res = new GenreResource($genre);
        return $this->sendSuccess(200, 'Genre updated successfully', $res);
    }
    /**
     * @OA\Delete(
     *     path="/api/genres/{id}",
     *     tags={"Genres"},
     *     summary="Delete genres",
     *     description="-",
     *     operationId="genres/delete",
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
        $genre = Genre::find($id);

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        if (!$genre) {
            return $this->sendError(404, 'Genre not found', !Gate::allows('admin'));
        }

        $genre->delete();
        return $this->sendError(200, 'Genre deleted successfully');
    }
}
