<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class AmountBase extends Entity
{
    protected string $currency = 'COP';
    protected float $total;

    public function __construct($data = [])
    {
        $this->load($data, ['currency', 'total']);
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function total(): float
    {
        return $this->total;
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->currency(),
            'total' => $this->total(),
        ];
    }
}
