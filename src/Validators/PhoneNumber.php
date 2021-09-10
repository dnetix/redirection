<?php

namespace Dnetix\Redirection\Validators;

class PhoneNumber
{
    public const VALIDATION_PATTERN = '/([0|\+?[0-9]{1,5})?([0-9 \(\)]{7,})([\(\)\w\d\. ]+)?/';

    public static function isValidNumber($number)
    {
        return (bool)preg_match(self::VALIDATION_PATTERN, $number);
    }
}
