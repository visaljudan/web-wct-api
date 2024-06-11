<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MoviePhoto\MoviePhotoResource;
use App\Http\Resources\MoviePhoto\MoviePhotoResourceCollection;
use App\Models\MoviePhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MoviePhotoController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/movie_photos",
     *     tags={"Movie_Photos"},
     *     summary="Get List movie_photos Data",
     *     description="enter your movie_photos here",
     *     operationId="Movie_Photos",
     *     @OA\Response(
     *         response="default",
     *         description="return array model movie_photos"
     *     )
     * )
     */
    public function index()
    {
        $moviePhotos = MoviePhoto::all();
        if ($moviePhotos->count() > 0) {
            $res = new MoviePhotoResourceCollection($moviePhotos);
            return $this->sendSuccess(200, 'Movie found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/movie_photos",
     *     tags={"Movie_Photos"},
     *     summary="movie_photos",
     *     description="movie_photos",
     *     operationId="movie_photos",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form movie_photos",
     *          @OA\JsonContent(
     *            required={"movie_id", "photo"},
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="photo", type="image"),
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
            'movie_id' => 'required|exists:movies,id',
            'photo_image_file' => 'nullable|required_without:photo_image_url|image|mimes:jpeg,png,jpg,gif,svg',
            'photo_image_url' => 'nullable|required_without:photo_image_file|string|url',
        ]);


        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $moviePhoto = MoviePhoto::create($request->all());

        $res = new MoviePhotoResource($moviePhoto);
        return $this->sendSuccess(201, 'Movie photo uploaded successfully', $res);
    }

    public function show($id)
    {
        $moviePhoto = MoviePhoto::find($id);

        if (!$moviePhoto) {
            return $this->sendError(404, 'Movie photo not found');
        }

        $res = new MoviePhotoResource($moviePhoto);
        return $this->sendSuccess(200, 'Movie photo found', $res);
    }

    public function update(Request $request, $id)
    {
        $moviePhoto = MoviePhoto::find($id);

        if (!$moviePhoto) {
            $this->sendError(404, 'Movie photo not found');
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'exists:movies,id',
            'photo_image_file' => 'nullable|required_without:photo_image_url|image|mimes:jpeg,png,jpg,gif,svg',
            'photo_image_url' => 'nullable|required_without:photo_image_file|string|url',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }


        $moviePhoto->movie_id  = $request->movie_id;
        $moviePhoto->photo_image_file = $request->photo_image_file;
        $moviePhoto->photo_image_url = $request->photo_image_url;
        $moviePhoto->save();

        $res = new MoviePhotoResource($moviePhoto);
        return $this->sendSuccess(200, 'Movie photo updated successfully', $res);
    }

    public function destroy($id)
    {
        $moviePhoto = MoviePhoto::find($id);


        if (!$moviePhoto) {
            return $this->sendError(404, 'Movie photo not found',);
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $moviePhoto->delete();
        return $this->sendSuccess(200, 'Movie photo deleted successfully');
    }

    public function movieIdPhoto($movieId)
    {

        $moviePhoto = MoviePhoto::where('movie_id', $movieId)->get();
        if (!$moviePhoto) {
            return $this->sendError(404, 'Movie photo not found');
        }

        $res = new MoviePhotoResourceCollection($moviePhoto);
        return $this->sendSuccess(200, 'Movie photo found', $res);
    }
}
