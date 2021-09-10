<?php

namespace Dnetix\Redirection\Traits;

trait ValidatorTrait
{
    private $validatorInstance;

    public function getValidator()
    {
        if (!$this->validatorInstance) {
            $this->validatorInstance = new $this->validator();
        }
        return $this->validatorInstance;
    }

    /**
     * Validates if this entity contains the required information.
     */
    public function isValid(&$fields = null, bool $silent = true): bool
    {
        return $this->getValidator()->isValid($this, $fields, $silent);
    }

    /**
     * Verifies if the object has all the values required, returns those who are lacking.
     */
    public function checkMissingFields($requiredFields = []): array
    {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                $missing[] = $field;
            }
        }
        return $missing;
    }
}
