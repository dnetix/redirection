<?php

namespace Dnetix\Redirection\Validators;

use Dnetix\Redirection\Entities\Recurring;

class RecurringValidator extends BaseValidator
{
    public const PERIOD_DAY = 'D';
    public const PERIOD_MONTH = 'M';
    public const PERIOD_YEAR = 'Y';
    public static $PERIODS = [
        self::PERIOD_DAY,
        self::PERIOD_MONTH,
        self::PERIOD_YEAR,
    ];

    /**
     * @param Recurring $entity
     * @param $fields
     * @return bool
     */
    public static function isValid($entity, &$fields)
    {
        $errors = [];
        if (!in_array($entity->periodicity(), self::$PERIODS)) {
            $errors[] = 'periodicity';
        }
        if (!self::isInteger($entity->interval())) {
            $errors[] = 'interval';
        }
        if (!$entity->nextPayment() || !self::isActualDate($entity->nextPayment())) {
            $errors[] = 'nextPayment';
        }
        if (!self::isInteger($entity->maxPeriods())) {
            $errors[] = 'maxPeriods';
        }
        if ($entity->dueDate() && !self::isActualDate($entity->dueDate())) {
            $errors[] = 'dueDate';
        }
        if ($errors) {
            $fields = $errors;
            return false;
        }
        return true;
    }
}
