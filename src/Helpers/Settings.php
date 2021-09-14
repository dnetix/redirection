<?php

namespace Dnetix\Redirection\Helpers;

use Dnetix\Redirection\Carrier\RestCarrier;
use Dnetix\Redirection\Carrier\SoapCarrier;
use Dnetix\Redirection\Contracts\Carrier;
use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class Settings extends Entity
{
    protected const TP_REST = 'rest';
    protected const TP_SOAP = 'soap';

    protected string $type = self::TP_REST;
    // Used for REST
    protected string $baseUrl = '';
    // Used for SOAP
    protected string $wsdl = '';
    protected string $location = '';

    protected int $timeout = 15;
    protected bool $verifySsl = true;

    protected string $login;
    protected string $tranKey;
    protected array $headers = [];

    protected ?LoggerInterface $logger = null;
    protected ?Client $client = null;

    protected ?Carrier $carrier = null;

    public function __construct(array $data)
    {
        if (!isset($data['login']) || !isset($data['tranKey'])) {
            throw PlacetoPayException::forDataNotProvided('No login or tranKey provided on gateway');
        }

        if (isset($data['baseUrl']) && !filter_var($data['baseUrl'], FILTER_VALIDATE_URL)) {
            if (substr($data['baseUrl'], -1) != '/') {
                $data['baseUrl'] .= '/';
            }
            throw PlacetoPayException::forDataNotProvided('No service URL provided to use');
        }

        if (isset($data['type']) && in_array($data['type'], [self::TP_SOAP, self::TP_REST])) {
            $this->type = $data['type'];
        }

        $allowedKeys = [
            'type',
            'baseUrl',
            'wsdl',
            'location',
            'timeout',
            'verifySsl',
            'login',
            'tranKey',
            'headers',
            'carrier',
        ];

        $this->load($data, $allowedKeys);
        $this->logger = new Logger($data['logger'] ?? null);
    }

    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    public function wsdl(): string
    {
        return $this->wsdl;
    }

    public function location(): ?string
    {
        return $this->location;
    }

    public function timeout(): int
    {
        return $this->timeout;
    }

    public function verifySsl(): bool
    {
        return $this->verifySsl;
    }

    public function tranKey(): string
    {
        return $this->tranKey;
    }

    public function logger(): Logger
    {
        return $this->logger;
    }

    public function toArray(): array
    {
        return [];
    }

    private function carrier(): Carrier
    {
        if ($this->carrier instanceof Carrier) {
            return $this->carrier;
        }

        if ($this->type == self::TP_SOAP) {
            $this->carrier = new SoapCarrier($this);
        } else {
            $this->carrier = new RestCarrier($this);
        }

        return $this->carrier;
    }
}
