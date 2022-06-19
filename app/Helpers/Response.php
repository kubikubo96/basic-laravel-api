<?php

namespace App\Helpers;

/**
 * Response Class helper
 */
class Response
{
    public static function success($data = [], $total = 0, $message = 'Successfully', $status = 200): array
    {
        return [
            'status' => $status,
            'state' => $status == 200 ? 1 : 0,
            'message' => $message,
            'total' => $total,
            'data' => $data,
        ];
    }

    public static function error($message = 'BAD_REQUEST', $status = 400): array
    {
        return self::success([], 0, $message, $status);
    }
}
