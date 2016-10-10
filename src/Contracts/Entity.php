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

}