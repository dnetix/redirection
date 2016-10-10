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
    protected $authentication;
    protected $config;

    public function __construct(Authentication $authentication, $config = [])
    {
        $this->authentication = $authentication;
        $this->config = $config;
    }

    public function authentication()
    {
        return $this->authentication;
    }

    protected function config()
    {
        return $this->config;
    }

    protected function asArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * @param RedirectRequest $redirectRequest
     * @return RedirectResponse
     */
    public abstract function request(RedirectRequest $redirectRequest);

    /**
     * @param int $requestId
     * @return RedirectInformation
     */
    public abstract function query($requestId);

    /**
     * @param CollectRequest $collectRequest
     * @return RedirectInformation
     */
    public abstract function collect(CollectRequest $collectRequest);

    /**
     * @param string $transactionId
     * @return ReverseResponse
     */
    public abstract function reverse($transactionId);

}