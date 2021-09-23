<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Traits\StatusTrait;

class SubscriptionInformation extends Entity
{
    use StatusTrait;

    /**
     * The type of this subscription could be token or account for the time being.
     */
    protected string $type;
    /**
     * @var NameValuePair[]
     */
    protected array $instrument = [];

    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->loadEntity($data['status'], 'status', Status::class);

        if (isset($data['instrument'])) {
            $this->setInstrument($data['instrument']);
        }
    }

    public function type(): string
    {
        return $this->type;
    }

    public function instrument(): array
    {
        return $this->instrument;
    }

    public function setInstrument($instrumentData): self
    {
        $this->instrument = [];
        if (isset($instrumentData['item'])) {
            $instrumentData = $instrumentData['item'];
        }

        foreach ($instrumentData as $nvp) {
            if (is_array($nvp)) {
                $nvp = new NameValuePair($nvp);
            }

            if ($nvp instanceof NameValuePair) {
                $this->instrument[] = $nvp;
            }
        }
        return $this;
    }

    public function instrumentToArray(): array
    {
        if ($this->instrument()) {
            $instrument = [];
            foreach ($this->instrument() as $field) {
                $instrument[] = ($field instanceof NameValuePair) ? $field->toArray() : null;
            }
            return $instrument;
        }
        return [];
    }

    /**
     * Parses the instrument as the proper entity, Keep in mind that can be null
     * if no instrument its provided.
     * @return Account|Token|null
     */
    public function parseInstrument()
    {
        $instrumentNVP = $this->instrument();
        if (!$instrumentNVP) {
            return null;
        }

        $data = [
            'status' => $this->status(),
        ];
        foreach ($instrumentNVP as $nvp) {
            $data[$nvp->keyword()] = $nvp->value();
        }

        if ($this->type() == 'token') {
            return new Token($data);
        } elseif ($this->type() == 'account') {
            return new Account($data);
        }
        return null;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type(),
            'status' => $this->status()->toArray(),
            'instrument' => $this->instrumentToArray(),
        ]);
    }
}
