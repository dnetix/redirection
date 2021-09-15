<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class Token extends Entity
{
    protected string $token = '';
    protected string $subtoken = '';
    protected string $franchise = '';
    protected string $franchiseName = '';
    protected string $issuerName = '';
    protected string $lastDigits = '';
    protected string $validUntil = '';
    // Just in case the token will be utilized
    protected string $cvv = '';
    protected int $installments = 0;

    public function __construct($data = [])
    {
        $this->load($data, ['token', 'subtoken', 'franchise', 'franchiseName', 'issuerName', 'lastDigits', 'validUntil', 'cvv', 'installments']);
    }

    public function token(): string
    {
        return $this->token;
    }

    public function subtoken(): string
    {
        return $this->subtoken;
    }

    public function franchise(): string
    {
        return $this->franchise;
    }

    public function franchiseName(): string
    {
        return $this->franchiseName;
    }

    public function issuerName(): string
    {
        return $this->issuerName;
    }

    public function lastDigits(): string
    {
        return $this->lastDigits;
    }

    public function validUntil(): string
    {
        return $this->validUntil;
    }

    public function cvv(): string
    {
        return $this->cvv;
    }

    public function installments(): string
    {
        return $this->installments;
    }

    public function expiration(): string
    {
        return date('m/y', strtotime($this->validUntil()));
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'token' => $this->token(),
            'subtoken' => $this->subtoken(),
            'franchise' => $this->franchise(),
            'franchiseName' => $this->franchiseName(),
            'issuerName' => $this->issuerName(),
            'lastDigits' => $this->lastDigits(),
            'validUntil' => $this->validUntil(),
            'cvv' => $this->cvv(),
            'installments' => $this->installments(),
        ]);
    }
}
