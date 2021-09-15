<?php

namespace Dnetix\Redirection\Message;

use Dnetix\Redirection\Entities\Instrument;

class CollectRequest extends RedirectRequest
{
    protected Instrument $instrument;

    protected string $returnUrl = '';
    protected string $ipAddress = '';
    protected string $userAgent = '';

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->loadEntity($data['instrument'], 'instrument', Instrument::class);
    }

    public function instrument(): Instrument
    {
        return $this->instrument;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'instrument' => $this->instrument() ? $this->instrument()->toArray() : null,
        ]);
    }
}
