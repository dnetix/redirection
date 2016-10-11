<?php


namespace Dnetix\Redirection\Contracts;


use Dnetix\Redirection\Traits\ValidatorTrait;

abstract class Entity
{
    use ValidatorTrait;

    /**
     * Extracts the information for the entity
     * @return array
     */
    public abstract function toArray();

    public static function arrayFilter($array)
    {
        return array_filter($array, function ($item) {
            return !empty($item) || $item === false || $item === 0;
        });
    }

}