<?php


namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\LoaderTrait;

class Transaction extends Entity
{
    use LoaderTrait;
    /**
     * @var Status
     */
    protected $status;
    /**
     * Reference as the commerce provides
     * @var string
     */
    protected $reference;
    /**
     * Reference for PlacetoPay
     * @var string
     */
    protected $internalReference;
    protected $paymentMethod;
    protected $paymentMethodName;
    protected $issuerName;
    /**
     * @var AmountConversion
     */
    protected $amount;
    protected $authorization;
    protected $processorFields;
    protected $receipt;
    protected $franchise;
    protected $refunded = false;

    public function __construct($data = [])
    {
        $this->load($data, ['reference', 'internalReference', 'paymentMethod', 'paymentMethodName', 'issuerName', 'authorization', 'receipt', 'franchise', 'refunded']);

        if (isset($data['status'])) {
            $this->setStatus($data['status']);
        }
        if (isset($data['amount'])) {
            $this->setAmount($data['amount']);
        }
    }

    public function status()
    {
        return $this->status;
    }

    public function reference()
    {
        return $this->reference;
    }

    public function internalReference()
    {
        return $this->internalReference;
    }

    public function paymentMethod()
    {
        return $this->paymentMethod;
    }

    public function paymentMethodName()
    {
        return $this->paymentMethodName;
    }

    public function issuerName()
    {
        return $this->issuerName;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function authorization()
    {
        return $this->authorization;
    }

    public function receipt()
    {
        return $this->receipt;
    }

    public function franchise()
    {
        return $this->franchise;
    }

    public function processorFields()
    {
        return $this->processorFields;
    }

    public function refunded()
    {
        return $this->refunded;
    }

    /**
     * Determines if the transaction information its valid, meaning the query was
     * successful not the transaction
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->status() && $this->status()->status() != Status::ST_ERROR;
    }

    public function setAmount($amount)
    {
        if (is_array($amount))
            $amount = new AmountConversion($amount);
        $this->amount = $amount;
        return $this;
    }

    /**
     * Sets the amount base as the amount conversion
     * @param $base
     * @return $this
     */
    public function setAmountBase($base)
    {
        if (is_array($base))
            $base = new AmountBase($base);
        $this->amount = (new AmountConversion())->setAmountBase($base);
        return $this;
    }

    public function setStatus($status)
    {
        if (is_array($status))
            $status = new Status($status);
        $this->status = $status;
        return $this;
    }

    public function toArray()
    {
        return [
            'status' => $this->status()->toArray(),
            'internalReference' => $this->internalReference(),
            'paymentMethod' => $this->paymentMethod(),
            'paymentMethodName' => $this->paymentMethodName(),
            'issuerName' => $this->issuerName(),
            'amount' => $this->amount() ? $this->amount()->toArray() : null,
            'authorization' => $this->authorization(),
            'reference' => $this->reference(),
            'receipt' => $this->receipt(),
            'franchise' => $this->franchise(),
            'refunded' => $this->refunded()
        ];
    }

}