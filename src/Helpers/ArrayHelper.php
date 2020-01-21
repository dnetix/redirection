<?php

namespace Dnetix\Redirection\Helpers;

class ArrayHelper
{
    /**
     * Transforms the object passed as array
     * @param $data
     * @return array
     */
    public static function asArray($data)
    {
        return json_decode(json_encode($data), true);
    }

    /**
     * Returns only the values that match the provided keys
     * @param $array
     * @param $keys
     * @return array
     */
    public static function only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }

    /**
     * Filters an array looking for null values and remove it corresponding key
     * @param $array
     * @return array
     */
    public static function filter($array)
    {
        return array_filter($array, function ($item) {
            return !empty($item) || $item === false || $item === 0 || $item == '0';
        });
    }
}
