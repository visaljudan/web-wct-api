<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Documentation ",
 *      description="API Documentation",
 *      @OA\Contact(
 *          email="srundavith@gmail.com"
 *      )
 * )
 */
class MainController extends Controller
{
    protected function sendSuccess($status = 200, $message = '', $data = [],): JsonResponse
    {
        return response()->json([
            'success' => true,
            'statusCode' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }


    protected function sendError($status = 400, $message = '', $error = [],): JsonResponse
    {
        return response()->json([
            'success' => false,
            'statusCode' => $status,
            'message' => $message,
            'error' => $error
        ]);
    }
}
