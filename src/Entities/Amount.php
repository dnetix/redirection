<?php

namespace Dnetix\Redirection\Entities;

class Amount extends AmountBase
{
    /**
     * @var TaxDetail[]
     */
    protected array $taxes = [];
    /**
     * @var AmountDetail[]
     */
    protected array $details = [];

    protected $tip;
    protected $insurance;

    public function __construct($data = [])
    {
        parent::__construct($data);
        if (isset($data['taxes'])) {
            $this->setTaxes($data['taxes']);
        }
        if (isset($data['details'])) {
            $this->setDetails($data['details']);
        }
    }

    public function taxes()
    {
        return $this->taxes;
    }

    public function details()
    {
        return $this->details;
    }

    public function setTaxes(array $taxes)
    {
        $return = [];
        foreach ($taxes as $tax) {
            if (is_array($tax)) {
                $tax = new TaxDetail($tax);
                $return[] = $tax;
            }
        }
        $this->taxes = $return;
        return $this;
    }

    public function setDetails($details)
    {
        $return = [];
        foreach ($details as $detail) {
            if (is_array($detail)) {
                $detail = new AmountDetail($detail);
            }

            $this->{$detail->kind()} = $detail->amount();
            $return[] = $detail;
        }
        $this->details = $return;
        return $this;
    }

    private function taxesToArray()
    {
        if ($this->taxes()) {
            $taxes = [];
            foreach ($this->taxes() as $tax) {
                $taxes[] = ($tax instanceof TaxDetail) ? $tax->toArray() : null;
            }
            return $taxes;
        }
        return null;
    }

    private function detailsToArray()
    {
        if ($this->details()) {
            $details = [];
            foreach ($this->details() as $detail) {
                $details[] = $detail->toArray();
            }
            return $details;
        }
        return null;
    }

    public function toArray(): array
    {
        return $this->arrayFilter(array_merge([
            'taxes' => $this->taxesToArray(),
            'details' => $this->detailsToArray(),
        ], parent::toArray()));
    }
}
