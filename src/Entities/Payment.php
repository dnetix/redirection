<?php


namespace Dnetix\Redirection\Entities;


use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\FieldsTrait;
use Dnetix\Redirection\Traits\LoaderTrait;

class Payment extends Entity
{
    use FieldsTrait, LoaderTrait;

    protected $reference;
    protected $description;
    /**
     * @var Amount
     */
    protected $amount;
    protected $allowPartial = false;
    /**
     * @var Person
     */
    protected $shipping;
    protected $items;
    /**
     * @var Recurring
     */
    protected $recurring;
    protected $discount;
    /**
     * @var Instrument
     */
    protected $instrument;

    public $subscribe = false;

    protected $agreement;
    protected $agreementType;
    /**
     * @var GDS
     */
    protected $gds;

    public function __construct($data = [])
    {
        $this->load($data, ['reference', 'description', 'allowPartial', 'subscribe', 'items', 'agreement', 'agreementType']);

        if (isset($data['amount'])) {
            $this->setAmount($data['amount']);
        }
        if (isset($data['recurring'])) {
            $this->setRecurring($data['recurring']);
        }
        if (isset($data['shipping'])) {
            $this->setShipping($data['shipping']);
        }
        if (isset($data['items'])) {
            $this->setItems($data['items']);
        }
        if (isset($data['fields'])) {
            $this->setFields($data['fields']);
        }
        if (isset($data['gds'])) {
            $this->setGDS($data['gds']);
        }
    }

    public function reference()
    {
        return $this->reference;
    }

    public function description()
    {
        return $this->description;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function agreement()
    {
        return $this->agreement;
    }

    public function agreementType()
    {
        return $this->agreementType;
    }

    public function gds()
    {
        return $this->gds;
    }

    /**
     * @return bool
     */
    public function allowPartial()
    {
        return filter_var($this->allowPartial, FILTER_VALIDATE_BOOLEAN);
    }

    public function shipping()
    {
        return $this->shipping;
    }

    public function items()
    {
        return $this->items;
    }

    public function recurring()
    {
        return $this->recurring;
    }

    public function subscribe()
    {
        return $this->subscribe;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setGDS($gds)
    {
        if (is_array($gds)) {
            $gds = new GDS($gds);
        }
        $this->gds = $gds;
        return $this;
    }

    public function setItems($items)
    {
        if ($items && is_array($items)) {
            if (isset($items['item'])) {
                $items = $items['item'];
            }

            $this->items = [];
            foreach ($items as $item) {
                if (is_array($item)) {
                    $item = new Item($item);
                }
                $this->items[] = $item;
            }
        }
        return $this;
    }

    public function itemsToArray()
    {
        if ($this->items() && is_array($this->items())) {
            $items = [];
            foreach ($this->items() as $item) {
                $items[] = $item->toArray();
            }
            return $items;
        } else {
            return null;
        }
    }

    public function toArray()
    {
        return [
            'reference' => $this->reference(),
            'description' => $this->description(),
            'amount' => $this->amount() ? $this->amount()->toArray() : null,
            'allowPartial' => $this->allowPartial,
            'shipping' => $this->shipping() ? $this->shipping()->toArray() : null,
            'items' => $this->itemsToArray(),
            'recurring' => $this->recurring() ? $this->recurring()->toArray() : null,
            'subscribe' => $this->subscribe(),
            'fields' => $this->fieldsToArray(),
            'agreement' => $this->agreement(),
            'agreementType' => $this->agreementType(),
            'gds' => $this->gds() ? $this->gds()->toArray() : null,
        ];
    }
}