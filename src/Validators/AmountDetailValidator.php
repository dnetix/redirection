<?php

namespace Dnetix\Redirection\Validators;

use Dnetix\Redirection\Entities\AmountDetail;

class AmountDetailValidator extends BaseValidator
{
    public const TP_DISCOUNT = 'discount';
    public const TP_ADDITIONAL = 'additional';
    public const TP_DEVOLUTION_BASE = 'vatDevolutionBase';
    public const TP_SHIPPING = 'shipping';
    public const TP_HANDLING_FEE = 'handlingFee';
    public const TP_INSURANCE = 'insurance';
    public const TP_GIFT_WRAP = 'giftWrap';
    public const TP_SUBTOTAL = 'subtotal';
    public const TP_FEE = 'fee';
    public const TP_TIP = 'tip';
    public static $TYPES = [
        self::TP_DISCOUNT,
        self::TP_ADDITIONAL,
        self::TP_DEVOLUTION_BASE,
        self::TP_SHIPPING,
        self::TP_HANDLING_FEE,
        self::TP_INSURANCE,
        self::TP_GIFT_WRAP,
        self::TP_SUBTOTAL,
        self::TP_FEE,
        self::TP_TIP,
    ];

    /**
     * @param AmountDetail $entity
     * @param $fields
     * @param bool $silent
     * @return bool
     */
    public static function isValid($entity, &$fields, $silent = true)
    {
        $errors = [];
        if (!$entity->kind() || !in_array($entity->kind(), self::$TYPES)) {
            $errors[] = 'kind';
        }

        if (!$entity->amount() || !is_numeric($entity->amount())) {
            $errors[] = 'amount';
        }

        if ($errors) {
            $fields = $errors;
            self::throwValidationException($errors, 'AmountDetail', $silent);
            return false;
        }
        return true;
    }
}
