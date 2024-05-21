<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MediaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mediaTypes = MediaType::all();

        if ($mediaTypes->count() > 0) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'mediaTypes' => $mediaTypes
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
            'media_type_name' => 'required|string|unique:media_types',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $mediaType = MediaType::create([
            'media_type_name' => $request->media_type_name,
        ]);

        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'message' => 'Media type created successfully',
            'mediaType' => $mediaType,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the media type by ID
        $mediaType = MediaType::find($id);

        // If media type not found, return error response
        if (!$mediaType) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Media type not found',
            ], 404);
        }

        // Return the media type data
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'mediaType' => $mediaType
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mediaType = MediaType::find($id);

        if (!$mediaType) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Media type not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'media_type_name' => 'required|string|max:255|unique:media_types,media_type_name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->filled('media_type_name') && $request->media_type_name !== $mediaType->media_type_name) {
            $mediaType->media_type_name = $request->media_type_name;
        } elseif ($request->filled('media_type_name')) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'New media type name is the same as the current one',
                'mediaType' => $mediaType,
            ], 422);
        }

        $mediaType->save();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Media type updated successfully',
            'mediaType' => $mediaType,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mediaType = MediaType::find($id);

        if (!$mediaType) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Media type not found',
            ], 404);
        }

        $mediaType->delete();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Media type deleted successfully',
        ], 200);
    }
}
