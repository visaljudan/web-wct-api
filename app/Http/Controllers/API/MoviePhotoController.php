<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MoviePhoto\MoviePhotoResource;
use App\Http\Resources\MoviePhoto\MoviePhotoResourceCollection;
use App\Models\MoviePhoto;
use Illuminate\Http\Request;
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
    // /**
    //  * Store a newly created resource in storage.
    //  */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Example: Allow only image files with max size 2048 KB
        ]);


        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $photoPath = $request->file('photo')->store('photos');

        $moviePhoto = MoviePhoto::create([
            'movie_id' => $request->movie_id,
            'photo_path' => $photoPath,
        ]);

        $res = new MoviePhotoResource($moviePhoto);
        return $this->sendSuccess(201, 'Movie photo uploaded successfully', $res);
    }
    // /**
    //  * Display the specified resource.
    //  */
    public function show($movieId)
    {
        $moviePhoto = MoviePhoto::where('movie_id', $movieId)->get();

        if (!$moviePhoto) {
            return $this->sendError(404, 'Movie photo not found');
        }

        $res = new MoviePhotoResourceCollection(($moviePhoto));
        return $this->sendSuccess(200, 'Movie photo not found', $res);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $moviePhoto = MoviePhoto::find($id);

        if (!$moviePhoto) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie photo not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'exists:movies,id',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Example: Allow only image files with max size 2048 KB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos');
            // Storage::delete($moviePhoto->photo_path); // Delete old photo file
            $moviePhoto->photo_path = $photoPath;
        }

        if ($request->filled('movie_id')) {
            $moviePhoto->movie_id = $request->movie_id;
        }

        $moviePhoto->save();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Movie photo updated successfully',
            'moviePhoto' => $moviePhoto,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $moviePhoto = MoviePhoto::find($id);

        if (!$moviePhoto) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie photo not found',
            ], 404);
        }

        // Storage::delete($moviePhoto->photo_path); // Delete associated photo file
        $moviePhoto->delete();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Movie photo deleted successfully',
        ], 200);
    }
}
