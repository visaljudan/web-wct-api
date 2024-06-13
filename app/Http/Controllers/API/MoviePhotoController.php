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
     *            required={"movie_id", "photo_image_file", "photo_image_url"},
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="photo_image_file", type="image"),
     *              @OA\Property(property="photo_image_url", type="string"),
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
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'photo_image_file' => 'nullable|required_without:photo_image_url|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust max size as per your requirements
            'photo_image_url' => 'nullable|required_without:photo_image_file|string|url',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Check user permissions
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        try {
            // Initialize variables for photo_image_url
            $photo_image = null;

            // Check if file is present before attempting to store
            if ($request->hasFile('photo_image_file')) {
                $file = $request->file('photo_image_file');
                $imagePath = $file->store('img'); // Store in 'img' directory
                $photo_image = env('AWS_CLOUDFRONT_URL') . "/" . $imagePath; // Example CloudFront URL
            } elseif ($request->has('photo_image_url')) {
                // Use provided URL if no file is uploaded
                $photo_image = $request->photo_image_url;
            }

            // Create the movie photo record
            $moviePhoto = MoviePhoto::create([
                'movie_id' => $request->movie_id,
                'photo_image' => $photo_image,
            ]);

            // Return a success response
            $res = new MoviePhotoResource($moviePhoto);
            return $this->sendSuccess(201, 'Movie photo created successfully', $res);
        } catch (\Exception $e) {
            // Handle any exceptions
            return $this->sendError(500, 'Failed to store movie photo');
        }
    }
    /**
     * @OA\Get(
     *     path="/api/movie_photos/{id}",
     *     tags={"Movie_Photos"},
     *     summary="Detail",
     *     description="-",
     *     operationId="movie_photos/GetById",
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
        $moviePhoto = MoviePhoto::find($id);

        if (!$moviePhoto) {
            return $this->sendError(404, 'Movie photo not found');
        }

        $res = new MoviePhotoResource($moviePhoto);
        return $this->sendSuccess(200, 'Movie photo found', $res);
    }
    /**
     * @OA\Put(
     *     path="/api/movie_photos/{id}",
     *     tags={"Movie_Photos"},
     *     summary="Update movie_photos",
     *     description="-",
     *     operationId="movie_photos/update",
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
     *             required={"movie_id", "photo_image_file", "photo_image_url"},
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="photo_image_file", type="image"),
     *              @OA\Property(property="photo_image_url", type="string"),
     *          ),
     *      ),
     *     @OA\Response(response="200", description="Success"),
     *         security={{"Bearer":{}}}
     * )
     */
    public function update(Request $request, $id)
    {

        // Find the movie photo record
        $moviePhoto = MoviePhoto::find($id);


        if (!$moviePhoto) {
            return $this->sendError(404, 'Movie Photo not found');
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'photo_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust max size as per your requirements
            'photo_image_url' => 'nullable|string|url',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Check user permissions
        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        try {

            // Handle photo_image_file update if provided
            if ($request->hasFile('photo_image_file')) {
                $file = $request->file('photo_image_file');
                $imagePath = $file->store('img'); // Store in 'img' directory
                $photo_image = env('AWS_CLOUDFRONT_URL') . "/" . $imagePath; // Example CloudFront URL
                $moviePhoto->photo_image = $photo_image;
            } elseif ($request->has('photo_image_url')) {
                // Update photo_image_url if provided
                $moviePhoto->photo_image = $request->photo_image_url;
            }

            // Update movie_id if provided
            $moviePhoto->movie_id = $request->movie_id;

            // Save the updated movie photo record
            $moviePhoto->save();

            // Return a success response
            $res = new MoviePhotoResource($moviePhoto);
            return $this->sendSuccess(200, 'Movie photo updated successfully', $res);
        } catch (\Exception $e) {
            // Handle any exceptions
            return $this->sendError(500, 'Failed to update movie photo');
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/movie_photos/{id}",
     *     tags={"Movie_Photos"},
     *     summary="Delete movie_photos",
     *     description="-",
     *     operationId="movie_photos/delete",
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
