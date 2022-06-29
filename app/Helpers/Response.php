<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

/**
 * Response Class helper
 */
class Response
{
    public static function success($data = [], $total = 0, $message = 'Successfully', $status = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'total' => $total,
            'data' => $data,
            'timestamp' => now()
        ], $status);
    }

    public static function error($error = 'BAD_REQUEST', $status = 400): JsonResponse
    {
        return response()->json([
            'error' => $error,
            'timestamp' => now()
        ], $status);
    }
}
