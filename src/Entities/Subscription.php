<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\FieldsTrait;

class Subscription extends Entity
{
    use FieldsTrait;

    protected string $reference = '';
    protected string $description = '';

    public function __construct(array $data = [])
    {
        $this->load($data, ['reference', 'description']);
        if (isset($data['fields'])) {
            $this->setFields($data['fields']);
        }
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'reference' => $this->reference(),
            'description' => $this->description(),
            'fields' => $this->fieldsToArray(),
        ]);
    }
}
