<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class Address extends Entity
{
    protected string $street = '';
    protected string $city = '';
    protected string $state = '';
    protected string $postalCode = '';
    protected string $country = '';
    protected string $phone = '';

    public function __construct($data = [])
    {
        $this->load($data, ['street', 'city', 'state', 'postalCode', 'phone', 'country']);
    }

    public function street(): string
    {
        return $this->street;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'street' => $this->street(),
            'city' => $this->city(),
            'state' => $this->state(),
            'postalCode' => $this->postalCode(),
            'country' => $this->country(),
            'phone' => $this->phone(),
        ]);
    }
}
