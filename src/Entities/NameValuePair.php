<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class NameValuePair extends Entity
{
    protected string $keyword;
    protected $value;
    protected string $displayOn = 'none';

    public function __construct($data = [])
    {
        $this->load($data, ['keyword', 'value', 'displayOn']);
    }

    public function keyword(): string
    {
        return $this->keyword;
    }

    /**
     * @return string|array
     */
    public function value()
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
