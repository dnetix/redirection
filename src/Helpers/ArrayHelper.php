<?php

namespace Dnetix\Redirection\Helpers;

class ArrayHelper
{
    /**
     * Transforms the object passed as array.
     */
    public static function asArray($data): array
    {
        return json_decode(json_encode($data), true);
    }

    /**
     * Returns only the values that match the provided keys.
     */
    public static function only(array $array, array $keys): array
    {
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Filters an array looking for null values and remove it corresponding key.
     */
    public static function filter(array $array): array
    {
        return array_filter($array, function ($item) {
            return !empty($item) || $item === false || $item === 0 || $item == '0';
        });
    }
}
