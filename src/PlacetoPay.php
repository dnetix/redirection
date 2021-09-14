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
     * @return RedirectResponse
     * @throws PlacetoPayException
     */
    public function request($redirectRequest)
    {
        if (is_array($redirectRequest)) {
            $redirectRequest = new RedirectRequest($redirectRequest);
        }

        if (!($redirectRequest instanceof RedirectRequest)) {
            throw PlacetoPayException::forDataNotProvided('Wrong class request');
        }

        return $this->carrier()->request($redirectRequest);
    }

    /**
     * @param int $requestId
     * @return RedirectInformation
     */
    public function query($requestId)
    {
        return $this->carrier()->query($requestId);
    }

    /**
     * @param CollectRequest|array $collectRequest
     * @return RedirectInformation
     * @throws PlacetoPayException
     */
    public function collect($collectRequest)
    {
        if (is_array($collectRequest)) {
            $collectRequest = new CollectRequest($collectRequest);
        }

        if (!($collectRequest instanceof CollectRequest)) {
            throw new PlacetoPayException('Wrong collect request');
        }

        return $this->carrier()->collect($collectRequest);
    }

    /**
     * @param string $internalReference
     * @return ReverseResponse
     */
    public function reverse($internalReference)
    {
        return $this->carrier()->reverse($internalReference);
    }

    public function readNotification(array $data = []): Notification
    {
        if (!$data) {
            try {
                $data = json_decode(file_get_contents('php://input'), true);
            } catch (\Exception $e) {
                throw new PlacetoPayException('Error constructing the information from the input');
            }
        }

        return new Notification($data, $this->settings->tranKey());
    }
}
