<?php


namespace Dnetix\Redirection\Entities;

class DispersionPayment extends Payment
{
    /**
     * @var Payment[]
     */
    protected $dispersion;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (isset($data['dispersion'])) {
            $this->setDispersion($data['dispersion']);
        }
    }

    public function dispersion()
    {
        return $this->dispersion;
    }

    public function setDispersion($data)
    {
        // TODO: Check possible failure
        foreach ($data as $payment) {
            $entity = new Payment($payment);
            $entity->setReference($this->reference())
                ->setDescription($this->description());
            $this->dispersion[] = $entity;
        }
        return $this;
    }

    protected function dispersionToArray()
    {
        $data = [];
        if ($this->dispersion) {
            foreach ($this->dispersion as $payment) {
                $data[] = $payment->toArray();
            }
        }
        return $data;
    }

    public function toArray()
    {
        return array_replace(parent::toArray(), [
            'dispersion' => $this->dispersionToArray(),
        ]);
    }
}
