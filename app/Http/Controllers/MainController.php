<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="Let's Watch API by Visal Judan (only)",
 *     version="1.0.0",
 *     description="Ah menh hz ah vith ot ban jouy tver teh",
 *     @OA\Contact(
 *         email="visal.nls2003@gmail.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
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
