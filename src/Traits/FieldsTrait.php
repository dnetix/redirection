<?php

namespace Dnetix\Redirection\Traits;

use Dnetix\Redirection\Entities\NameValuePair;

trait FieldsTrait
{
    /**
     * @var NameValuePair[]
     */
    protected array $fields = [];

    /**
     * @return NameValuePair[]
     */
    public function fields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fieldsData)
    {
        if (isset($fieldsData['item'])) {
            $fieldsData = $fieldsData['item'];
        }

        $this->fields = [];
        foreach ($fieldsData as $nvp) {
            if (is_array($nvp)) {
                $nvp = new NameValuePair($nvp);
            }

            if ($nvp instanceof NameValuePair) {
                $this->fields[] = $nvp;
            }
        }
        return $this;
    }

    public function fieldsToArray(): array
    {
        if ($this->fields()) {
            $fields = [];
            foreach ($this->fields() as $field) {
                $fields[] = ($field instanceof NameValuePair) ? $field->toArray() : null;
            }
            return $fields;
        }
        return [];
    }

    public function fieldsToKeyValue($nvps = null): array
    {
        if (!$nvps) {
            $nvps = $this->fields();
        }

        if ($nvps) {
            $fields = [];
            foreach ($nvps as $field) {
                $fields[$field->keyword()] = $field->value();
            }
            return $fields;
        }

        return [];
    }

    public function addField($nvp): self
    {
        if (is_array($nvp)) {
            $nvp = new NameValuePair($nvp);
        }

        if ($nvp instanceof NameValuePair) {
            $this->fields[] = $nvp;
        }

        return $this;
    }
}
