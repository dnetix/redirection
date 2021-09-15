<?php

namespace Dnetix\Redirection\Helpers;

use Psr\Log\LoggerInterface;

/**
 * @method void emergency(string $message, array $context = [])
 * @method void alert(string $message, array $context = [])
 * @method void critical(string $message, array $context = [])
 * @method void error(string $message, array $context = [])
 * @method void warning(string $message, array $context = [])
 * @method void notice(string $message, array $context = [])
 * @method void info(string $message, array $context = [])
 * @method void debug(string $message, array $context = [])
 */
class Logger
{
    protected ?LoggerInterface $logger = null;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function log($level, $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->log($level, '(REDIRECTION_LOG) ' . $message, $this->cleanUp($context));
        }
    }

    public function __call($name, $arguments)
    {
        $this->log($name, $arguments[0], $arguments[1] ?? []);
    }

    public function cleanUp($mixed): array
    {
        return $mixed ?: [];
    }
}
