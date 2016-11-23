<?php


namespace Dnetix\Redirection\Message;


use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Entities\SubscriptionInformation;
use Dnetix\Redirection\Entities\Transaction;
use Dnetix\Redirection\Traits\StatusTrait;

class RedirectInformation extends Entity
{
    use StatusTrait;

    public $requestId;
    /**
     * @var RedirectRequest
     */
    public $request;
    /**
     * @var Transaction[]
     */
    public $payment;
    /**
     * @var SubscriptionInformation
     */
    public $subscription;

    public function __construct($data = [])
    {
        if (isset($data['requestId']))
            $this->requestId = $data['requestId'];

        $this->setStatus($data['status']);

        if (isset($data['request']))
            $this->setRequest($data['request']);

        if (isset($data['payment']))
            $this->setPayment($data['payment']);

        if (isset($data['subscription']))
            $this->setSubscription($data['subscription']);
    }

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

    public function subscription()
    {
        return $this->subscription;
    }

    public function setRequest($request)
    {
        if (is_array($request))
            $request = new RedirectRequest($request);
        $this->request = $request;
        return $this;
    }

    private function setPayment($payments)
    {
        if ($payments) {
            $this->payment = [];

            if ($payments['transaction'])
                $payments = $payments['transaction'];

            foreach ($payments as $payment) {
                $this->payment[] = new Transaction($payment);
            }
        }
        return $this;
    }

    /**
     * @param SubscriptionInformation|array $subscription
     * @return $this
     */
    public function setSubscription($subscription)
    {
        if (is_array($subscription))
            $subscription = new SubscriptionInformation($subscription);
        $this->subscription = $subscription;
        return $this;
    }

    private function paymentToArray()
    {
        if (!$this->payment() || !is_array($this->payment()))
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
            'request' => $this->request() ? $this->request()->toArray() : null,
            'payment' => $this->paymentToArray(),
            'subscription' => $this->subscription() ? $this->subscription()->toArray() : null,
        ]);
    }
}