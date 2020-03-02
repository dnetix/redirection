<?php

namespace Dnetix\Redirection\Validators;

use Dnetix\Redirection\Exceptions\EntityValidationFailException;
use Dnetix\Redirection\Exceptions\ValidationFailException;

class BaseValidator
{
    const PATTERN_REFERENCE = '/^[\d\w\-\.,\$#\/\\\'!]{1,32}$/';
    const PATTERN_DESCRIPTION = '/^[a-zñáéíóúäëïöüàèìòùÑÁÉÍÓÚÄËÏÖÜÀÈÌÒÙÇçÃã\s\d\.,\$#\&\-\_(\)\/\%\+\\\']{2,250}$/i';

    public static function isValidIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    public static function isEmpty($value)
    {
        return empty($value);
    }

    public static function matchPattern($value, $pattern)
    {
        if (is_object($value) || is_array($value)) {
            throw new ValidationFailException('Value should be string, object provided ' . serialize($value));
        }
        return preg_match($pattern, $value);
    }

    public static function isValidString($value, $min, $required)
    {
        if ($required && self::isEmpty($value)) {
            return false;
        }
        if ($value && mb_strlen($value) < $min) {
            return false;
        }

        return true;
    }

    public static function isValidLengthString($value, $min, $max, $required = false)
    {
        if ($required && self::isEmpty($value)) {
            return false;
        }
        if ($value && (mb_strlen($value) < $min || mb_strlen($value) > $max)) {
            return false;
        }

        return true;
    }

    public static function isInteger($value)
    {
        return !!filter_var($value, FILTER_VALIDATE_INT);
    }

    public static function isNumeric($value)
    {
        return is_numeric($value);
    }

    public static function isActualDate($date, $minDifference = -1)
    {
        return strtotime($date) - time() > $minDifference;
    }

    public static function parseDate($date, $format = 'c')
    {
        $time = strtotime($date);
        if (!$time) {
            return false;
        }
        return date($format, $time);
    }

    public static function throwValidationException($fields, $from, $silent = true, $message = null)
    {
        if (!$silent) {
            throw new EntityValidationFailException($fields, $from, $message);
        }
    }
}
