<?php

namespace Dnetix\Redirection\Exceptions;

use Throwable;

class PlacetoPayServiceException extends PlacetoPayException
{
    public static function fromServiceException(Throwable $e)
    {
        return new self('Error handling operation', 100, $e);
    }

    public static function forInvalidResponse(?int $statusCode, string $jsonError, string $body): self
    {
        return new self(sprintf(
            'Invalid response from service [status: %s] [error: %s] [length: %d]',
            $statusCode ?? 'unknown',
            $jsonError,
            strlen($body)
        ), 100);
    }
}
