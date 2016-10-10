<?php


namespace Dnetix\Redirection\Message;


use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Entities\Transaction;

class ReverseResponse
{
    /**
     * @var Status
     */
    public $status;
    /**
     * @var Transaction
     */
    public $payment;


    public function status()
    {
        return $this->status;
    }

    public function payment()
    {
        return $this->payment;
    }

    public function __construct($data = [])
    {
        $this->setStatus($data['status']);

        if (isset($data['payment']))
            $this->setPayment($data['payment']);
    }

    public function setStatus($status)
    {
        if (is_array($status))
            $status = new Status($status);
        $this->status = $status;
        return $this;
    }

    private function setPayment($payment)
    {
        if (is_array($payment))
            $payment = new Transaction($payment);
        $this->payment = $payment;
        return $this;
    }

    public function isSuccessful()
    {
        return $this->status()->status() != Status::ST_ERROR;
    }

    public function toArray()
    {
        return array_filter([
            'status' => $this->status() ? $this->status()->toArray() : null,
            'payment' => $this->payment() ? $this->payment()->toArray() : null,
        ]);
    }

}