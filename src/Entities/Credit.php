<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class Credit extends Entity
{
    protected string $code;
    protected string $type;
    protected string $groupCode;
    /**
     * When first created from the service.
     * @var int
     */
    protected int $installment;

    public function __construct(array $data = [])
    {
        $this->load($data, ['code', 'type', 'groupCode', 'installment']);
    }

    public function code(): string
    {
        return $this->code;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function groupCode(): string
    {
        return $this->groupCode;
    }

    public function installment(): int
    {
        return $this->installment;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code(),
            'type' => $this->type(),
            'groupCode' => $this->groupCode(),
            'installment' => $this->installment(),
        ];
    }
}
