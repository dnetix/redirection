<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\StatusTrait;

class Transaction extends Entity
{
    use StatusTrait;

    /**
     * Reference as the commerce provides.
     */
    protected string $reference;
    /**
     * Reference for PlacetoPay.
     */
    protected string $internalReference = '';
    protected string $paymentMethod = '';
    protected string $paymentMethodName = '';
    protected string $issuerName = '';
    protected ?Discount $discount = null;
    protected AmountConversion $amount;
    protected string $authorization = '';
    protected string $receipt = '';
    protected string $franchise = '';
    protected bool $refunded = false;
    /**
     * @var NameValuePair[]
     */
    protected array $processorFields = [];

    public function __construct($data = [])
    {
        $this->load($data, ['reference', 'internalReference', 'paymentMethod', 'paymentMethodName', 'issuerName', 'authorization', 'receipt', 'franchise', 'refunded']);

        $this->loadEntity($data['status'], 'status', Status::class);
        $this->loadEntity($data['amount'] ?? null, 'amount', AmountConversion::class);
        $this->loadEntity($data['discount'] ?? null, 'discount', Discount::class);

        if (isset($data['processorFields'])) {
            $this->setProcessorFields($data['processorFields']);
        }
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function internalReference(): string
    {
        return $this->internalReference;
    }

    public function paymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function paymentMethodName(): string
    {
        return $this->paymentMethodName;
    }

    public function issuerName(): string
    {
        return $this->issuerName;
    }

    public function amount(): AmountConversion
    {
        return $this->amount;
    }

    public function authorization(): string
    {
        return $this->authorization;
    }

    public function receipt(): string
    {
        return $this->receipt;
    }

    public function franchise(): string
    {
        return $this->franchise;
    }

    public function processorFields(): array
    {
        return $this->processorFields;
    }

    public function refunded(): bool
    {
        return $this->refunded;
    }

    public function discount(): ?Discount
    {
        return $this->discount;
    }

    /**
     * Determines if the transaction information its valid, meaning the query was
     * successful not the transaction.
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->status() && $this->status()->status() != Status::ST_ERROR;
    }

    /**
     * Determines if the transaction has been approved.
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status() && $this->status()->status() == Status::ST_APPROVED;
    }

    public function setProcessorFields($data): self
    {
        if (isset($data['item'])) {
            $data = $data['item'];
        }

        if (is_array($data)) {
            foreach ($data as $nvp) {
                $this->processorFields[] = new NameValuePair($nvp);
            }
        }

        return $this;
    }

    public function processorFieldsToArray(): array
    {
        if ($this->processorFields()) {
            $fields = [];
            foreach ($this->processorFields() as $field) {
                $fields[] = ($field instanceof NameValuePair) ? $field->toArray() : null;
            }
            return $fields;
        }
        return [];
    }

    /**
     * Parses the processorFields as a key value array.
     */
    public function additionalData(): array
    {
        if ($this->processorFields()) {
            $data = [];
            foreach ($this->processorFields() as $field) {
                $data[$field->keyword()] = $field->value();
            }
            return $data;
        }
        return [];
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
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
            'refunded' => $this->refunded(),
            'discount' => $this->discount() ? $this->discount()->toArray() : null,
            'processorFields' => $this->processorFieldsToArray(),
        ]);
    }
}
