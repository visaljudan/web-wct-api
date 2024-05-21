<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MoviePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoviePhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $moviePhotos = MoviePhoto::all();
        if ($moviePhotos->count() > 0) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'moviePhotos' => $moviePhotos
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
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Example: Allow only image files with max size 2048 KB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $photoPath = $request->file('photo')->store('photos');

        $moviePhoto = MoviePhoto::create([
            'movie_id' => $request->movie_id,
            'photo_path' => $photoPath,
        ]);

        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'message' => 'Movie photo uploaded successfully',
            'moviePhoto' => $moviePhoto,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $moviePhoto = MoviePhoto::find($id);

        if (!$moviePhoto) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Movie photo not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'moviePhoto' => $moviePhoto,
        ], 200);
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
