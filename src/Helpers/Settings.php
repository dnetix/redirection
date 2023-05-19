<?php

namespace Dnetix\Redirection\Helpers;

use Dnetix\Redirection\Carrier\Authentication;
use Dnetix\Redirection\Carrier\RestCarrier;
use Dnetix\Redirection\Contracts\Carrier;
use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use GuzzleHttp\Client;

class Settings extends Entity
{
    protected string $baseUrl = '';

    protected int $timeout = 15;
    protected bool $verifySsl = true;

    protected string $login;
    protected string $tranKey;
    protected array $headers = [];
    protected array $authAdditional = [];

    protected ?Logger $logger = null;
    protected ?Client $client = null;

    protected ?Carrier $carrier = null;

    public function __construct(array $data)
    {
        if (!isset($data['login']) || !isset($data['tranKey'])) {
            throw PlacetoPayException::forDataNotProvided('No login or tranKey provided on gateway');
        }

        if (!isset($data['baseUrl']) || !filter_var($data['baseUrl'], FILTER_VALIDATE_URL)) {
            throw PlacetoPayException::forDataNotProvided('No service URL provided to use');
        }

        if (substr($data['baseUrl'], -1) != '/') {
            $data['baseUrl'] .= '/';
        }

        $allowedKeys = [
            'baseUrl',
            'timeout',
            'verifySsl',
            'login',
            'tranKey',
            'headers',
            'client',
            'authAdditional',
        ];

        $this->load($data, $allowedKeys);
        $this->logger = new Logger($data['logger'] ?? null);
    }

    public function baseUrl(string $endpoint = ''): string
    {
        return $this->baseUrl . $endpoint;
    }

    public function timeout(): int
    {
        return $this->timeout;
    }

    public function verifySsl(): bool
    {
        return $this->verifySsl;
    }

    public function login(): string
    {
        return $this->login;
    }

    public function tranKey(): string
    {
        return $this->tranKey;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function client(): Client
    {
        if (!$this->client) {
            $this->client = new Client([
                'timeout' => $this->timeout(),
                'connect_timeout' => $this->timeout(),
                'verify' => $this->verifySsl(),
            ]);
        }
        return $this->client;
    }

    public function logger(): Logger
    {
        return $this->logger;
    }

    public function toArray(): array
    {
        return [];
    }

    public function carrier(): Carrier
    {
        if ($this->carrier instanceof Carrier) {
            return $this->carrier;
        }

        $this->carrier = new RestCarrier($this);

        return $this->carrier;
    }

    public function authentication(): Authentication
    {
        return new Authentication([
            'login' => $this->login(),
            'tranKey' => $this->tranKey(),
            'authAdditional' => $this->authAdditional,
        ]);
    }
}
