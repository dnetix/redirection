<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class AmountConversion extends Entity
{
    protected ?AmountBase $from = null;
    protected ?AmountBase $to = null;
    protected float $factor = 1;

    public function __construct($data = [])
    {
        if (isset($data['from'])) {
            $this->setFrom($data['from']);
        }

        if (isset($data['to'])) {
            $this->setTo($data['to']);
        }

        if (isset($data['factor'])) {
            $this->setFactor($data['factor']);
        }
    }

    /**
     * Helper function to quickly set all the values.
     * @param $base
     * @return $this
     */
    public function setAmountBase($base)
    {
        if (is_array($base)) {
            $base = new AmountBase($base);
        }

        $this->setTo($base);
        $this->setFrom($base);
        $this->setFactor(1);
        return $this;
    }

    /**
     * @return AmountBase
     */
    public function from(): AmountBase
    {
        return $this->from;
    }

    /**
     * @return AmountBase
     */
    public function to(): AmountBase
    {
        return $this->to;
    }

    public function factor()
    {
        return $this->factor;
    }

    public function setFrom($from)
    {
        $this->loadEntity($from, 'from', AmountBase::class);
        return $this;
    }

    public function setTo($to)
    {
        $this->loadEntity($to, 'to', AmountBase::class);
        return $this;
    }

    public function setFactor($factor)
    {
        $this->factor = $factor;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'from' => $this->from()->toArray(),
            'to' => $this->to()->toArray(),
            'factor' => $this->factor(),
        ];
    }
}
