<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\FieldsTrait;

class Payment extends Entity
{
    use FieldsTrait;

    protected string $reference;
    protected string $description = '';
    protected ?Amount $amount = null;
    protected bool $allowPartial = false;
    protected ?Person $shipping = null;
    /**
     * @var Item[]
     */
    protected array $items = [];
    protected ?Recurring $recurring = null;
    protected ?Discount $discount = null;
    protected ?Instrument $instrument = null;
    public bool $subscribe = false;
    protected ?int $agreement = null;
    protected string $agreementType = '';

    /** @var PaymentModifier[] */
    protected array $modifiers = [];

    public function __construct(array $data = [])
    {
        $this->load($data, ['reference', 'description', 'allowPartial', 'subscribe', 'agreement', 'agreementType']);

        $this->loadEntity($data['amount'] ?? null, 'amount', Amount::class);
        $this->loadEntity($data['recurring'] ?? null, 'recurring', Recurring::class);
        $this->loadEntity($data['shipping'] ?? null, 'shipping', Person::class);
        $this->loadEntity($data['discount'] ?? null, 'discount', Discount::class);

        if (isset($data['items'])) {
            $this->setItems($data['items']);
        }
        if (isset($data['fields'])) {
            $this->setFields($data['fields']);
        }
        if (isset($data['modifiers']) && is_array($data['modifiers'])) {
            $this->setModifiers($data['modifiers']);
        }
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function amount(): ?Amount
    {
        return $this->amount;
    }

    public function agreement(): ?int
    {
        return $this->agreement;
    }

    public function agreementType(): string
    {
        return $this->agreementType;
    }

    public function allowPartial(): bool
    {
        return filter_var($this->allowPartial, FILTER_VALIDATE_BOOLEAN);
    }

    public function shipping(): ?Person
    {
        return $this->shipping;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function recurring(): ?Recurring
    {
        return $this->recurring;
    }

    public function subscribe(): bool
    {
        return $this->subscribe;
    }

    public function discount(): ?Discount
    {
        return $this->discount;
    }

    public function setReference($reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function setDescription($description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setItems(array $items): self
    {
        if (isset($items['item'])) {
            $items = $items['item'];
        }
        $this->items = array_map(function ($data) {
            return is_array($data) ? new Item($data) : $data;
        }, $items);

        return $this;
    }

    public function itemsToArray(): array
    {
        if ($this->items() && is_array($this->items())) {
            return array_map(function (Item $item) {
                return $item->toArray();
            }, $this->items());
        }

        return [];
    }

    /**
     * @return PaymentModifier[]
     */
    public function modifiers(): array
    {
        return $this->modifiers;
    }

    public function setModifiers(array $modifiers): self
    {
        $this->modifiers = [];

        foreach ($modifiers as $modifier) {
            $this->addModifier($modifier);
        }

        return $this;
    }

    public function addModifier($modifier): self
    {
        if (is_array($modifier)) {
            $modifier = new PaymentModifier($modifier);
        }

        if ($modifier instanceof PaymentModifier) {
            $this->modifiers[] = $modifier;
        }

        return $this;
    }

    public function modifiersToArray(): array
    {
        $modifiers = [];
        foreach ($this->modifiers as $modifier) {
            $modifiers[] = $modifier->toArray();
        }

        return $modifiers;
    }

    public function modifier(string $type, ?string $code = null): ?PaymentModifier
    {
        foreach ($this->modifiers as $modifier) {
            if ($modifier->type() === $type && (!$code || $code === $modifier->code())) {
                return $modifier;
            }
        }

        return null;
    }

    public function toArray(): array
    {
        return self::arrayFilter([
            'agreement' => $this->agreement(),
            'agreementType' => $this->agreementType(),
            'reference' => $this->reference(),
            'description' => $this->description(),
            'amount' => $this->amount() ? $this->amount()->toArray() : null,
            'shipping' => $this->shipping() ? $this->shipping()->toArray() : null,
            'items' => $this->itemsToArray(),
            'recurring' => $this->recurring() ? $this->recurring()->toArray() : null,
            'discount' => $this->discount() ? $this->discount()->toArray() : null,
            'fields' => $this->fieldsToArray(),
            'subscribe' => $this->subscribe() ?: null,
            'allowPartial' => $this->allowPartial ?: null,
            'modifiers' => $this->modifiersToArray(),
        ]);
    }
}
