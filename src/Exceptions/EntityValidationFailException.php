<?php

namespace Dnetix\Redirection\Exceptions;

class EntityValidationFailException extends ValidationFailException
{
    protected $fields;
    protected $from;

    public function __construct($fields = [], $from = null, $message = null)
    {
        $this->fields = $fields;
        $this->from = $from;

        if (!$message) {
            $message = 'Validation fail on entity ' . $from . ' values (' . implode(', ', $fields) . ')';
        }

        parent::__construct($message);
    }

    public function fields()
    {
        return $this->fields;
    }

    public function from()
    {
        return $this->from;
    }
}
