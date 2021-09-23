<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\StatusTrait;

class Account extends Entity
{
    use StatusTrait;

    protected string $bankCode;
    protected string $bankName;
    protected string $accountType = '';
    protected string $accountNumber = '';

    public function __construct($data = [])
    {
        $this->load($data, ['bankCode', 'bankName', 'accountType', 'accountNumber']);
        $this->loadEntity($data['status'] ?? null, 'status', Status::class);
    }

    public function bankCode(): string
    {
        return $this->bankCode;
    }

    public function bankName(): string
    {
        return $this->bankName;
    }

    public function accountType(): string
    {
        return $this->accountType;
    }

    public function accountNumber(): string
    {
        return $this->accountNumber;
    }

    public function toArray(): array
    {
        return array_filter([
            'status' => $this->status()->toArray(),
            'bankCode' => $this->bankCode(),
            'bankName' => $this->bankName(),
            'accountType' => $this->accountType(),
            'accountNumber' => $this->accountNumber(),
        ]);
    }

    public function type(): string
    {
        return 'account';
    }

    /**
     * Last digits for the instrument subscribed in order to display to the
     * user.
     */
    public function lastDigits(): string
    {
        return substr($this->accountNumber(), -4);
    }
}
