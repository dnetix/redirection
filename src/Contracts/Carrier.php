<?php

namespace Dnetix\Redirection\Contracts;

use Dnetix\Redirection\Carrier\Authentication;
use Dnetix\Redirection\Message\CollectRequest;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectRequest;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\Message\ReverseResponse;

abstract class Carrier
{
    protected $auth;
    protected $config;

    public function __construct(Authentication $auth, $config = [])
    {
        $this->auth = $auth;
        $this->config = $config;
    }

    protected function config()
    {
        return $this->config;
    }

    protected function asArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    protected function authentication()
    {
        return $this->auth;
    }

    /**
     * @param RedirectRequest $redirectRequest
     * @return RedirectResponse
     */
    abstract public function request(RedirectRequest $redirectRequest);

    /**
     * @param int $requestId
     * @return RedirectInformation
     */
    abstract public function query($requestId);

    /**
     * @param CollectRequest $collectRequest
     * @return RedirectInformation
     */
    abstract public function collect(CollectRequest $collectRequest);

    /**
     * @param string $transactionId
     * @return ReverseResponse
     */
    abstract public function reverse($transactionId);
}
