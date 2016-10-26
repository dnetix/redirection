<?php


namespace Dnetix\Redirection\Entities;


use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\LoaderTrait;

class Token extends Entity
{
    use LoaderTrait;

    /**
     * @var Status
     */
    protected $status;
    protected $token;
    protected $subtoken;
    protected $franchiseName;
    protected $issuerName;
    protected $lastDigits;
    protected $validUntil;
    // Just in case the token will be utilized
    protected $cvv;
    protected $installments;

    public function __construct($data = [])
    {
        $this->load($data, ['token', 'subtoken', 'franchiseName', 'issuerName', 'lastDigits', 'validUntil', 'cvv', 'installments']);

        if (isset($data['status']))
            $this->setStatus($data['status']);
    }

    public function status()
    {
        return $this->status;
    }

    public function token()
    {
        return $this->token;
    }

    public function subtoken()
    {
        return $this->subtoken;
    }

    public function franchiseName()
    {
        return $this->franchiseName;
    }

    public function issuerName()
    {
        return $this->issuerName;
    }

    public function lastDigits()
    {
        return $this->lastDigits;
    }

    public function validUntil()
    {
        return $this->validUntil;
    }

    public function cvv()
    {
        return $this->cvv;
    }

    public function installments()
    {
        return $this->installments;
    }

    public function isSuccessful()
    {
        return $this->status()->status() == 'OK';
    }

    public function setStatus($status)
    {
        if (is_array($status))
            $status = new Status($status);
        $this->status = $status;
        return $this;
    }

    public function toArray()
    {
        return $this->arrayFilter([
            'status' => $this->status() ? $this->status()->toArray() : null,
            'token' => $this->token(),
            'subtoken' => $this->subtoken(),
            'franchiseName' => $this->franchiseName(),
            'issuerName' => $this->issuerName(),
            'lastDigits' => $this->lastDigits(),
            'validUntil' => $this->validUntil(),
        ]);
    }

}