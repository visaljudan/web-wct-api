<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\MovieVideo\MovieVideoResource;
use App\Http\Resources\MovieVideo\MovieVideoResourceCollection;
use App\Models\MovieVideo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MovieVideoController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/movie-videos",
     *     tags={"Movie-Videos"},
     *     summary="Get List movie-videos Data",
     *     description="enter your movie-videos here",
     *     operationId="movie-videos",
     *     @OA\Response(
     *         response="default",
     *         description="return array model movie-videos"
     *     )
     * )
     */
    public function index()
    {
        $movieVideos = MovieVideo::all();

        if ($movieVideos->count() > 0) {
            $res = new MovieVideoResourceCollection($movieVideos);
            return $this->sendSuccess(200, 'Movie video found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }
    /**
     * @OA\Post(
     *     path="/api/movie-videos",
     *     tags={"Movie-Videos"},
     *     summary="movie-videos",
     *     description="'movie-videos",
     *     operationId="'Movie-Videos",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form movie-videos",
     *          @OA\JsonContent(
     *            required={"movie_id", "title", "video"},
     *              @OA\Property(property="movie_id", type="string"),
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="video", type="string"),
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
            'video_file' => 'nullable|required_without:video_url|file|mimes:mp4,avi,mov',
            'video_url' => 'nullable|required_without:video_file|string',
            'season_number' => 'nullable|integer',
            'episode_number' => 'nullable|integer',
            'part_number' => 'nullable|integer',
            'type' => 'required|string|in:movie,trailer',
            'official' => 'required|boolean',
            'subscription' => 'required|boolean',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        try {
            $video = null;
            // Check if file is present before attempting to store
            if ($request->hasFile('video_file')) {
                $file = $request->file('video_file');
                $videoPath = $file->store('videos');

                $video = env('AWS_CLOUDFRONT_URL') . "/" . $videoPath;
            } else {
                $video = $request->video_url;
            }

            $movieVideo = MovieVideo::create([
                'movie_id' => $request->movie_id,
                // 'video_file' => $video,
                // 'video_url' => $request->video_url,
                'video' => $video,
                'season_number' => $request->season_number,
                'episode_number' => $request->episode_number,
                'part_number' => $request->part_number,
                'type' => $request->type,
                'official' => $request->official,
                'subscription' => $request->subscription,
                'subscription_start_date' => $request->subscription_start_date,
                'subscription_end_date' => $request->subscription_end_date,
            ]);

            $res = new MovieVideoResource($movieVideo);
            return $this->sendSuccess(201, 'Movie video created successfully', $res);
        } catch (\Exception $e) {
            return $this->sendError(500, 'Failed to store movie video');
        }
    }



    /**
     * @OA\Get(
     *     path="/api/movie-videos/{id}",
     *     tags={"Movie-Videos"},
     *     summary="Detail",
     *     description="-",
     *     operationId="movie-videos/GetById",
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
        $movieVideo = MovieVideo::find($id);

        if (!$movieVideo) {
            return $this->sendError(404, 'Movie video not found');
        }

        $res = new MovieVideoResource($movieVideo);
        return $this->sendSuccess(200, 'Movie video found', $res);
    }
    /**
     * @OA\Put(
     *     path="/api/movie-videos/{id}",
     *     tags={"Movie-Videos"},
     *     summary="Update movie-videos",
     *     description="-",
     *     operationId="movie-videos/update",
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
     *             required={"title", "video"},
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="video", type="string"),
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
        $movieVideo = MovieVideo::find($id);

        if (!$movieVideo) {
            return $this->sendError(404,  'Movie video not found');
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov',
            'video_url' => 'nullable|string',
            'season_number' => 'nullable|integer',
            'episode_number' => 'nullable|integer',
            'part_number' => 'nullable|integer',
            'type' => 'required|string|in:movie,trailer',
            'official' => 'required|boolean',
            'subscription' => 'required|boolean',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date',
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
            // Find the movie video record
            $movieVideo = MovieVideo::findOrFail($id);

            // Update movie_id if provided
            $movieVideo->movie_id = $request->movie_id;

            // Handle video_file update if provided
            if ($request->hasFile('video_file')) {
                $file = $request->file('video_file');
                $videoPath = $file->store('videos');

                $videoUrl = env('AWS_CLOUDFRONT_URL') . "/" . $videoPath;
                $movieVideo->video = $videoUrl;
            } elseif ($request->has('video_url')) {
                // Update video_url if provided
                $movieVideo->video = $request->video_url;
            }

            // Update other fields
            $movieVideo->season_number = $request->season_number;
            $movieVideo->episode_number = $request->episode_number;
            $movieVideo->part_number = $request->part_number;
            $movieVideo->type = $request->type;
            $movieVideo->official = $request->official;
            $movieVideo->subscription = $request->subscription;
            $movieVideo->subscription_start_date = $request->subscription_start_date;
            $movieVideo->subscription_end_date = $request->subscription_end_date;

            // Save the updated movie video record
            $movieVideo->save();

            // Return a success response
            $res = new MovieVideoResource($movieVideo);
            return $this->sendSuccess(200, 'Movie video updated successfully', $res);
        } catch (\Exception $e) {
            // Handle any exceptions
            return $this->sendError(500, 'Failed to update movie video');
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/movie-videos/{id}",
     *     tags={"Movie-Videos"},
     *     summary="Delete movie-videos",
     *     description="-",
     *     operationId="movie-videos/delete",
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
    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy($id)
    {
        $movieVideo = MovieVideo::find($id);

        if (!$movieVideo) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie video not found',
            ], 404);
        }

        // Storage::delete($movieVideo->video_path); // Delete associated video file
        $movieVideo->delete();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Movie video deleted successfully',
        ], 200);
    }


    public function movieIdTrailer($movieId)
    {
        $movieVideos = MovieVideo::where('movie_id', $movieId)->where('type', 'trailer')->get();

        if ($movieVideos->isEmpty()) {
            return $this->sendError(404, "This movie doesn't have any videos.");
        }

        $res = new MovieVideoResourceCollection($movieVideos);
        return $this->sendSuccess(200, 'Movie video found', $res);
    }
}
