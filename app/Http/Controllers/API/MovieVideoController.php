<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MovieVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movieVideos = MovieVideo::all();
        if ($movieVideos->count() > 0) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'mediaTypes' => $movieVideos
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'statusCode' => 400,
                'message' => 'No Record Found'
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4|max:2048', // Example: Allow only MP4 files with max size 2048 KB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $videoPath = $request->file('video')->store('videos');

        $movieVideo = MovieVideo::create([
            'movie_id' => $request->movie_id,
            'title' => $request->title,
            'video_path' => $videoPath,
        ]);

        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'message' => 'Movie video created successfully',
            'movieVideo' => $movieVideo,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movieVideo = MovieVideo::find($id);

        if (!$movieVideo) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie video not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'movieVideo' => $movieVideo,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $movieVideo = MovieVideo::find($id);

        if (!$movieVideo) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie video not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'video' => 'file|mimes:mp4|max:2048', // Example: Allow only MP4 files with max size 2048 KB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // if ($request->hasFile('video')) {
        //     $videoPath = $request->file('video')->store('videos');
        //     Storage::delete($movieVideo->video_path); // Delete old video file
        //     $movieVideo->video_path = $videoPath;
        // }

        $movieVideo->title = $request->title;
        $movieVideo->save();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Movie video updated successfully',
            'movieVideo' => $movieVideo,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
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
}
