<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\LoaderTrait;
use Dnetix\Redirection\Validators\AddressValidator;

class Address extends Entity
{
    protected $validator = AddressValidator::class;
    use LoaderTrait;
    protected $street;
    protected $city;
    protected $state;
    protected $postalCode;
    protected $country;
    protected $phone;

    public function __construct($data = [])
    {
        $this->load($data, ['street', 'city', 'state', 'postalCode', 'phone', 'country']);
        parent::__construct($data);
    }

    public function street()
    {
        return $this->street;
    }

    public function city()
    {
        return $this->city;
    }

    public function state()
    {
        return $this->state;
    }

    public function postalCode()
    {
        return $this->postalCode;
    }

    public function country()
    {
        return $this->country;
    }

    public function phone()
    {
        return AddressValidator::normalizePhone($this->phone);
    }

    public function toArray()
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
