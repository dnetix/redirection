<?php


namespace Dnetix\Redirection\Contracts;


use Dnetix\Redirection\Carrier\Authentication;
use Dnetix\Redirection\Carrier\SoapCarrier;
use Dnetix\Redirection\Message\CollectRequest;
use Dnetix\Redirection\Message\Notification;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectRequest;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\Message\ReverseResponse;

abstract class Gateway
{
    public $authentication;
    /**
     * @var Carrier
     */
    protected $carrier;

    public function __construct($config = [])
    {
        // Exception
        if (!isset($config['login']) || !isset($config['tranKey']))
            throw new \Exception('No login or tranKey provided');

        $this->authentication = new Authentication($config);
        $this->carrier = new SoapCarrier($this->authentication, $config);
    }

    /**
     * @param RedirectRequest|array $redirectRequest
     * @return RedirectResponse
     */
    public abstract function request($redirectRequest);

    /**
     * @param int $requestId
     * @return RedirectInformation
     */
    public abstract function query($requestId);

    /**
     * @param CollectRequest|array $collectRequest
     * @return RedirectInformation
     */
    public abstract function collect($collectRequest);

    /**
     * @param string $internalReference
     * @return ReverseResponse
     */
    public abstract function reverse($internalReference);

    /**
     * Change the web service to use for the connection
     * @param string $type can be SOAP or REST
     * @return $this
     */
    public function using($type)
    {
    }

    public function carrier()
    {
        return $this->carrier;
    }

    public function readNotification($data = null)
    {
        if (!$data)
            $data = $_POST;

        return new Notification($data, $this->authentication->key());
    }

}