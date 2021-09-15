<?php

namespace Dnetix\Redirection\Entities;

class DispersionPayment extends Payment
{
    /**
     * @var Payment[]
     */
    protected array $dispersion = [];

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (isset($data['dispersion'])) {
            $this->setDispersion($data['dispersion']);
        }
    }

    public function dispersion(): array
    {
        return $this->dispersion;
    }

    public function setDispersion($data): self
    {
        foreach ($data as $payment) {
            $entity = new Payment($payment);
            $entity->setReference($this->reference())
                ->setDescription($this->description());
            $this->dispersion[] = $entity;
        }
        return $this;
    }

    protected function dispersionToArray(): array
    {
        $data = [];
        if ($this->dispersion) {
            foreach ($this->dispersion as $payment) {
                $data[] = $payment->toArray();
            }
        }
        return $data;
    }

    public function toArray(): array
    {
        return self::arrayFilter(array_replace(parent::toArray(), [
            'dispersion' => $this->dispersionToArray(),
        ]));
    }
}
