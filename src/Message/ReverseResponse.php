<?php

namespace Dnetix\Redirection\Message;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Entities\Transaction;
use Dnetix\Redirection\Traits\StatusTrait;

class ReverseResponse extends Entity
{
    use StatusTrait;

    protected ?Transaction $payment = null;

    public function payment(): ?Transaction
    {
        return $this->payment;
    }

    public function __construct($data = [])
    {
        $this->loadEntity($data['status'] ?? null, 'status', Status::class);
        $this->loadEntity($data['payment'] ?? null, 'payment', Transaction::class);
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'status' => $this->status()->toArray(),
            'payment' => $this->payment() ? $this->payment()->toArray() : null,
        ]);
    }
}
