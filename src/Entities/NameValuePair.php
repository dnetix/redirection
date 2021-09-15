<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\LoaderTrait;

class NameValuePair extends Entity
{
    protected string $keyword;
    protected string $value = '';
    protected string $displayOn = 'none';

    public function __construct($data = [])
    {
        $this->load($data, ['keyword', 'value', 'displayOn']);
    }

    public function keyword(): string
    {
        return $this->keyword;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function displayOn(): string
    {
        return $this->displayOn;
    }

    public function toArray(): array
    {
        return [
            'keyword' => $this->keyword(),
            'value' => $this->value(),
            'displayOn' => $this->displayOn(),
        ];
    }
}
