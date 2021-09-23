<?php

namespace Dnetix\Redirection\Message;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Traits\StatusTrait;

class Notification extends Entity
{
    use StatusTrait;

    protected string $requestId;
    protected string $reference;
    protected string $signature;

    private string $tranKey;

    public function __construct(array $data, string $tranKey)
    {
        $this->load($data, ['requestId', 'reference', 'signature']);
        $this->loadEntity($data['status'], 'status', Status::class);

        $this->tranKey = $tranKey;
    }

    public function requestId(): string
    {
        return $this->requestId;
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function signature(): string
    {
        return $this->signature;
    }

    public function makeSignature(): string
    {
        return sha1($this->requestId() . $this->status()->status() . $this->status()->date() . $this->tranKey);
    }

    public function isValidNotification(): bool
    {
        return $this->signature() == $this->makeSignature();
    }

    /**
     * Extracts the information for the entity.
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status()->toArray(),
            'requestId' => $this->requestId(),
            'reference' => $this->reference(),
            'signature' => $this->signature(),
        ];
    }
}
