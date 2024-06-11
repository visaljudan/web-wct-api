<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\TVShow\TVShowResource;
use App\Http\Resources\TVShow\TVShowResourceCollection;
use App\Models\TVShow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class TvShowController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/tv_shows",
     *     tags={"Tv_Shows"},
     *     summary="Get List Data",
     *     description="enter your  here",
     *     operationId="tv_shows",
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    public function index()
    {
        $tvShows = TVShow::all();

        if ($tvShows->count() > 0) {
            $res = new TVShowResourceCollection($tvShows);
            return $this->sendSuccess(200, 'TV Shows Found', $res);
        } else {
            return $this->sendError(404, 'No Records Found');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/tv_shows",
     *     tags={"Tv_Shows"},
     *     summary="tv_shows",
     *     description="tv_shows",
     *     operationId="Tv_Shows",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form tv_shows",
     *          @OA\JsonContent(
     *            required={"tv_show_name"},
     *              @OA\Property(property="tv_show_name", type="string"),
     *            
     *          ),
     *      ),
     *    @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tv_show_name' => 'required|string|unique:tv_shows',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $tvShow = TVShow::create($request->all());

        $res = new TVShowResource($tvShow);
        return $this->sendSuccess(201, 'TV show created successfully', $res);
    }

    /**
     * @OA\Get(
     *     path="/api/tv_shows/{id}",
     *     tags={"Tv_Shows"},
     *     summary="Detail",
     *     description="-",
     *     operationId="tv_shows/GetById",
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
        $tvShow = TVShow::find($id);

        if (!$tvShow) {
            return $this->sendError(404, 'TV show not found');
        }

        $res = new TVShowResource($tvShow);
        return $this->sendSuccess(200, 'TV Show found', $res);
    }

    /**
     * @OA\Put(
     *     path="/api/tv_shows{id}",
     *     tags={"Tv_Shows"},
     *     summary="Update artist",
     *     description="-",
     *     operationId="tv_shows/update",
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
     *             required={"tv_show_name"},
     *              @OA\Property(property="tv_show_name", type="string"),
     *             
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */

    public function update(Request $request, $id)
    {
        $tvShow = TVShow::find($id);

        if (!$tvShow) {
            return $this->sendError(404, 'TV show not found');
        }

        $validator = Validator::make($request->all(), [
            'tv_show_name' => 'required|string|max:255|unique:tv_shows,tv_show_name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $tvShow->update($request->all());

        $res = new TVShowResource($tvShow);
        return $this->sendSuccess(200, 'TV show updated successfully', $res);
    }

    /**
     * @OA\Delete(
     *     path="/api/tv_shows/{id}",
     *     tags={"Tv_Shows"},
     *     summary="Delete artist",
     *     description="-",
     *     operationId="tv_shows/delete",
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
     * )
     */
    public function destroy($id)
    {
        $tvShow = TVShow::find($id);

        if (!$tvShow) {
            return $this->sendError(404, 'TV show not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $tvShow->delete();
        return $this->sendSuccess(200, 'TV show deleted successfully');
    }
}
