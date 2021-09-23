<?php

namespace Dnetix\Redirection\Message;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Traits\StatusTrait;

class RedirectResponse extends Entity
{
    use StatusTrait;

    protected string $requestId = '';
    protected string $processUrl = '';

    public function __construct($data = [])
    {
        $this->load($data, ['requestId', 'processUrl']);
        $this->loadEntity($data['status'], 'status', Status::class);
    }

    /**
     * Unique transaction code for this request.
     */
    public function requestId(): string
    {
        return $this->requestId;
    }

    /**
     * URL to consume when the gateway requires redirection.
     */
    public function processUrl(): string
    {
        return $this->processUrl;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'status' => $this->status()->toArray(),
            'requestId' => $this->requestId(),
            'processUrl' => $this->processUrl(),
        ]);
    }
}
