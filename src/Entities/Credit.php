<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\LoaderTrait;

class Credit extends Entity
{
    use LoaderTrait;

    protected $code;
    protected $type;
    protected $groupCode;
    /**
     * When first created from the service
     * @var int
     */
    protected $installment;

    public function __construct($data)
    {
        $this->load($data, ['code', 'type', 'groupCode', 'installment']);

        parent::__construct($data);
    }

    public function code()
    {
        return $this->code;
    }

    public function type()
    {
        return $this->type;
    }

    public function groupCode()
    {
        return $this->groupCode;
    }

    public function installment()
    {
        return $this->installment;
    }

    public function toArray()
    {
        return [
            'code' => $this->code(),
            'type' => $this->type(),
            'groupCode' => $this->groupCode(),
            'installment' => $this->installment(),
        ];
    }
}

