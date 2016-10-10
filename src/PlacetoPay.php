<?php


namespace Dnetix\Redirection;


use Dnetix\Redirection\Contracts\Gateway;
use Dnetix\Redirection\Message\CollectRequest;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectRequest;
use Dnetix\Redirection\Message\ReverseResponse;

class PlacetoPay extends Gateway
{

    /**
     * @param RedirectRequest|array $redirectRequest
     * @return \Dnetix\Redirection\Message\RedirectResponse
     */
    public function request($redirectRequest)
    {
        if (is_array($redirectRequest))
            $redirectRequest = new RedirectRequest($redirectRequest);

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
     */
    public function collect($collectRequest)
    {
        if (is_array($collectRequest))
            $collectRequest = new CollectRequest($collectRequest);

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

}