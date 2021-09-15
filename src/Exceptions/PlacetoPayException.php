<?php

namespace Dnetix\Redirection\Exceptions;

use Exception;
use Throwable;

class PlacetoPayException extends Exception
{
    public static function readException(Throwable $e)
    {
        return $e->getMessage() . ' ON ' . $e->getFile() . ' LINE ' . $e->getLine() . ' [' . get_class($e) . ']';
    }

    public static function forDataNotProvided(string $message = ''): self
    {
        return new self($message);
    }
}
