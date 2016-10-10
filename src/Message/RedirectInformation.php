<?php


namespace Dnetix\Redirection\Message;


use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Entities\Token;
use Dnetix\Redirection\Entities\Transaction;

class RedirectInformation
{
    public $requestId;
    /**
     * @var Status
     */
    public $status;
    /**
     * @var RedirectRequest
     */
    public $request;
    /**
     * @var Transaction[]
     */
    public $payment;
    /**
     * @var Token
     */
    public $token;

    public function requestId()
    {
        return $this->requestId;
    }

    public function status()
    {
        return $this->status;
    }

    public function request()
    {
        return $this->request;
    }

    public function payment()
    {
        return $this->payment;
    }

    public function token()
    {
        return $this->token;
    }

    public function __construct($data = [])
    {
        if (isset($data['requestId']))
            $this->requestId = $data['requestId'];

        $this->setStatus($data['status']);
        $this->setRequest($data['request']);

        if (isset($data['payment']))
            $this->setPayment($data['payment']);

        if (isset($data['token']))
            $this->setToken($data['token']);
    }

    public function setStatus($status)
    {
        if (is_array($status))
            $status = new Status($status);
        $this->status = $status;
        return $this;
    }

    public function setRequest($request)
    {
        if (is_array($request))
            $request = new RedirectRequest($request);
        $this->request = $request;
        return $this;
    }

    private function setPayment($payment)
    {
        $this->payment = $payment;
    }

    public function setToken($token)
    {
        if (is_array($token))
            $token = new Token($token);
        $this->token = $token;
        return $this;
    }

    private function paymentToArray()
    {
        if(!$this->payment() || !is_array($this->payment()))
            return null;

        $payments = [];
        foreach ($this->payment() as $payment) {
            $payments[] = $payment->toArray();
        }
        return $payments ?: null;
    }

    public function isSuccessful()
    {
        return $this->status()->status() != Status::ST_ERROR;
    }

    public function toArray()
    {
        return array_filter([
            'requestId' => $this->requestId(),
            'status' => $this->status() ? $this->status()->toArray() : null,
            'request' => $this->request()->toArray(),
            'payment' => $this->paymentToArray(),
            'token' => $this->token() ? $this->token()->toArray() : null
        ]);
    }
}