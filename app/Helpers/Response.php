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
            'total' => $total,
            'data' => $data,
            'status' => $status,
            'message' => $message,
            "timestamp" => now()
        ], $status);
    }

    public static function error($message = 'BAD_REQUEST', $status = 400): JsonResponse
    {
        return self::success([], 0, $message, $status);
    }
}
