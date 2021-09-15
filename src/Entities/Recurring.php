<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class Recurring extends Entity
{
    /**
     * Frequency to resubmit the transaction.
     * Y = annual  M = monthly  D = daily.
     * @var string
     */
    protected string $periodicity;
    protected int $interval;
    protected string $nextPayment = '';
    /**
     * Depends on the number of times that it makes the charge, corresponds to maximum times that the recurrence
     * will happen. If you do not want to set up should indicated -1.
     * You must specify this parameter or dueDate.
     */
    protected ?int $maxPeriods = null;
    protected string $dueDate = '';
    protected string $notificationUrl = '';

    public function __construct($data = [])
    {
        $this->load($data, ['periodicity', 'interval', 'maxPeriods', 'nextPayment', 'dueDate', 'notificationUrl']);
    }

    public function periodicity(): string
    {
        return $this->periodicity;
    }

    public function interval(): int
    {
        return $this->interval;
    }

    public function nextPayment(): string
    {
        return $this->nextPayment;
    }

    public function maxPeriods(): ?int
    {
        return $this->maxPeriods;
    }

    public function dueDate(): string
    {
        return $this->dueDate;
    }

    public function notificationUrl(): string
    {
        return $this->notificationUrl;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'periodicity' => $this->periodicity(),
            'interval' => $this->interval(),
            'nextPayment' => $this->nextPayment(),
            'maxPeriods' => $this->maxPeriods(),
            'dueDate' => $this->dueDate(),
            'notificationUrl' => $this->notificationUrl(),
        ]);
    }
}
