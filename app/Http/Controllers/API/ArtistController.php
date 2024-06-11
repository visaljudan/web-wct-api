<?php
//Api Done (Add to aws s3)
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Artist\ArtistResource;
use App\Http\Resources\Artist\ArtistResourceCollection;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;


class ArtistController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/artists",
     *     tags={"Artists"},
     *     summary="Get List Artists Data",
     *     description="enter your Artists here",
     *     operationId="Artists",
     *     @OA\Response(
     *         response="default",
     *         description="return array model Artists"
     *     )
     * )
     */
    public function index()
    {
        $artists = Artist::all();
        if ($artists->count() > 0) {
            $res = new ArtistResourceCollection($artists);
            return $this->sendSuccess(200, 'Artist Found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }
    /**
     * @OA\Post(
     *     path="/api/artists",
     *     tags={"Artists"},
     *     summary="artists",
     *     description="artists",
     *     operationId="artists",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form artists",
     *          @OA\JsonContent(
     *            required={"artist_name", "artist_profile"},
     *              @OA\Property(property="artist_name", type="string"),
     *              @OA\Property(property="artist_profile", type="string"),
     *          ),
     *      ),
     *    @OA\Response(response="200", description="Success"),
     *     security={{"Bearer":{}}}
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'profile_image_file' => 'nullable|required_without:profile_image_url|image|mimes:jpeg,png,jpg,gif,svg',
            'profile_image_url' => 'nullable|required_without:profile_image_file|string|url',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $artist = Artist::create($request->all());

        $res = new ArtistResource($artist);
        return $this->sendSuccess(200, 'Artist created successfully', $res);
    }
    /**
     * @OA\Get(
     *     path="/api/artists/{id}",
     *     tags={"Artists"},
     *     summary="Detail",
     *     description="-",
     *     operationId="artist/GetById",
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
        $artist = Artist::find($id);

        if (!$artist) {
            return $this->sendError(404, 'Artist not found');
        }

        $res = new ArtistResource($artist);
        return $this->sendSuccess(200, 'Artist Found', $res);
    }
    /**
     * @OA\Put(
     *     path="/api/artists/{id}",
     *     tags={"Artists"},
     *     summary="Update artist",
     *     description="-",
     *     operationId="Artist/update",
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
     *             required={"artist_name", "artist_profile"},
     *              @OA\Property(property="artist_name", type="string"),
     *              @OA\Property(property="artist_profile", type="string"),
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return $this->sendError(404, 'Artist not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'profile_image_file' => 'nullable|required_without:profile_image_url|image|mimes:jpeg,png,jpg,gif,svg',
            'profile_image_url' => 'nullable|required_without:profile_image_file|string|url',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $artist->name  = $request->name;
        $artist->profile_image_file = $request->profile_image_file;
        $artist->profile_image_url = $request->profile_image_url;
        $artist->save();

        $res = new ArtistResource($artist);
        return $this->sendSuccess(200, 'Artist updated successfully', $res);
    }
    /**
     * @OA\Delete(
     *     path="/api/artists/{id}",
     *     tags={"Artists"},
     *     summary="Delete artist",
     *     description="-",
     *     operationId="artist/delete",
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
     * )
     */
    public function destroy(string $id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return $this->sendError(404, 'Artist not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $artist->delete();
        return $this->sendSuccess(200, 'Artist deleted successfully');
    }
}
