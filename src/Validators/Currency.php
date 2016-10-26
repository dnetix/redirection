<?php


namespace Dnetix\Redirection\Validators;


class Currency extends BaseValidator
{

    const CUR_COP = 'COP';
    const CUR_USD = 'USD';
    const CUR_MXN = 'MXN';
    const CUR_AUD = 'AUD';

    public static $CURRENCIES = [
        self::CUR_COP,
        self::CUR_USD,
        self::CUR_MXN,
        self::CUR_AUD,
    ];

    public static function isValidCurrency($currency)
    {
        return in_array($currency, self::$CURRENCIES);
    }

}