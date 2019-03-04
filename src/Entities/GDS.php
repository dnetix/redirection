<?php


namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\LoaderTrait;

class GDS extends Entity
{
    use LoaderTrait;

    protected $code;
    protected $session;
    protected $pnr;
    protected $airline;

    public function __construct($data = [])
    {
        $this->load($data, ['code', 'session', 'pnr', 'airline']);
    }

    public function code()
    {
        return $this->code;
    }

    public function session()
    {
        return $this->session;
    }

    public function pnr()
    {
        return $this->pnr;
    }

    public function airline()
    {
        return $this->airline;
    }

    public function toArray()
    {
        return [
            'code' => $this->code(),
            'session' => $this->session(),
            'pnr' => $this->pnr(),
            'airline' => $this->airline(),
        ];
    }
}
