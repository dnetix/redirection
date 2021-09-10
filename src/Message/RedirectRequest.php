<?php

namespace Dnetix\Redirection\Message;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Entities\DispersionPayment;
use Dnetix\Redirection\Entities\Person;
use Dnetix\Redirection\Entities\Subscription;
use Dnetix\Redirection\Traits\FieldsTrait;

class RedirectRequest extends Entity
{
    use FieldsTrait;

    protected string $locale = 'es_CO';
    protected ?Person $payer = null;
    protected ?Person $buyer = null;
    protected ?DispersionPayment $payment = null;
    protected ?Subscription $subscription = null;
    protected string $returnUrl;
    protected string $paymentMethod = '';
    protected string $cancelUrl = '';
    protected string $ipAddress;
    protected string $userAgent;
    protected string $expiration;
    protected bool $captureAddress = false;
    protected bool $skipResult = false;
    protected bool $noBuyerFill = false;

    public function __construct($data = [])
    {
        // Setting the default values
        if (!isset($data['expiration'])) {
            $this->expiration = date('c', strtotime('+1 day'));
        }

        $this->load($data, ['returnUrl', 'paymentMethod', 'cancelUrl', 'ipAddress', 'userAgent', 'expiration', 'captureAddress', 'skipResult', 'noBuyerFill']);

        if (isset($data['locale'])) {
            $this->setLocale($data['locale']);
        }

        $this->loadEntity($data['payer'] ?? null, 'payer', Person::class);
        $this->loadEntity($data['buyer'] ?? null, 'buyer', Person::class);
        $this->loadEntity($data['payment'] ?? null, 'payment', DispersionPayment::class);
        $this->loadEntity($data['subscription'] ?? null, 'subscription', Subscription::class);

        if (isset($data['fields'])) {
            $this->setFields($data['fields']);
        }
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function language(): string
    {
        return strtoupper(substr($this->locale(), 0, 2));
    }

    public function payer(): ?Person
    {
        return $this->payer;
    }

    public function buyer(): ?Person
    {
        return $this->buyer;
    }

    public function payment(): ?DispersionPayment
    {
        return $this->payment;
    }

    public function subscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function cancelUrl(): string
    {
        return $this->cancelUrl;
    }

    public function returnUrl(): string
    {
        return $this->returnUrl;
    }

    public function ipAddress(): string
    {
        return $this->ipAddress;
    }

    public function userAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * A redirect request itself doesnt have a reference, but it should
     * know how to get it.
     * @return mixed
     */
    public function reference(): string
    {
        if ($this->payment()) {
            return $this->payment()->reference();
        }
        return $this->subscription()->reference();
    }

    public function setLocale($locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function setReturnUrl($returnUrl): self
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function setCancelUrl($cancelUrl): self
    {
        $this->cancelUrl = $cancelUrl;
        return $this;
    }

    public function setExpiration($expiration): self
    {
        $this->expiration = $expiration;
        return $this;
    }

    public function setUserAgent($userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function setIpAddress($ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * Returns the expiration datetime for this request.
     */
    public function expiration(): string
    {
        return $this->expiration;
    }

    public function paymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function captureAddress(): bool
    {
        return $this->captureAddress;
    }

    public function skipResult(): bool
    {
        return filter_var($this->skipResult, FILTER_VALIDATE_BOOLEAN);
    }

    public function noBuyerFill(): bool
    {
        return filter_var($this->noBuyerFill, FILTER_VALIDATE_BOOLEAN);
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'locale' => $this->locale(),
            'payer' => $this->payer() ? $this->payer()->toArray() : null,
            'buyer' => $this->buyer() ? $this->buyer()->toArray() : null,
            'payment' => $this->payment() ? $this->payment()->toArray() : null,
            'subscription' => $this->subscription() ? $this->subscription()->toArray() : null,
            'fields' => $this->fieldsToArray(),
            'returnUrl' => $this->returnUrl(),
            'paymentMethod' => $this->paymentMethod(),
            'cancelUrl' => $this->cancelUrl(),
            'ipAddress' => $this->ipAddress(),
            'userAgent' => $this->userAgent(),
            'expiration' => $this->expiration(),
            'captureAddress' => $this->captureAddress(),
            'skipResult' => $this->skipResult(),
            'noBuyerFill' => $this->noBuyerFill(),
        ]);
    }
}
