<?php

namespace Dnetix\Redirection\Contracts;

use Dnetix\Redirection\Helpers\Settings;
use Dnetix\Redirection\Message\CollectRequest;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectRequest;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\Message\ReverseResponse;

abstract class Carrier
{
    protected Settings $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    abstract public function request(RedirectRequest $redirectRequest): RedirectResponse;

    abstract public function query(string $requestId): RedirectInformation;

    abstract public function collect(CollectRequest $collectRequest): RedirectInformation;

    abstract public function reverse(string $transactionId): ReverseResponse;
}
