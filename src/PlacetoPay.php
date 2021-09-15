<?php

namespace Dnetix\Redirection;

use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Dnetix\Redirection\Helpers\Settings;
use Dnetix\Redirection\Message\CollectRequest;
use Dnetix\Redirection\Message\Notification;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectRequest;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\Message\ReverseResponse;

class PlacetoPay
{
    protected Settings $settings;

    public function __construct(array $data)
    {
        $this->settings = new Settings($data);
    }

    /**
     * @param RedirectRequest|array $redirectRequest
     * @throws PlacetoPayException
     */
    public function request($redirectRequest): RedirectResponse
    {
        if (is_array($redirectRequest)) {
            $redirectRequest = new RedirectRequest($redirectRequest);
        }

        if (!($redirectRequest instanceof RedirectRequest)) {
            throw PlacetoPayException::forDataNotProvided('Wrong class request');
        }

        return $this->settings->carrier()->request($redirectRequest);
    }

    public function query(int $requestId): RedirectInformation
    {
        return $this->settings->carrier()->query($requestId);
    }

    /**
     * @param CollectRequest|array $collectRequest
     * @throws PlacetoPayException
     */
    public function collect($collectRequest): RedirectInformation
    {
        if (is_array($collectRequest)) {
            $collectRequest = new CollectRequest($collectRequest);
        }

        if (!($collectRequest instanceof CollectRequest)) {
            throw new PlacetoPayException('Wrong collect request');
        }

        return $this->settings->carrier()->collect($collectRequest);
    }

    public function reverse(string $internalReference): ReverseResponse
    {
        return $this->settings->carrier()->reverse($internalReference);
    }

    public function readNotification(array $data): Notification
    {
        return new Notification($data, $this->settings->tranKey());
    }
}
