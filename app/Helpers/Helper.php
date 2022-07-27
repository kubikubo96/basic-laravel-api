<?php

namespace App\Helpers;

/**
 * Request Class helper
 */
class Helper
{
    public static function cleanOptionQuery(array $data): array
    {
        foreach ($data as $key => $item) {
            if (empty($item)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
