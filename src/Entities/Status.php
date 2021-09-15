<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class Status extends Entity
{
    public const ST_OK = 'OK';
    public const ST_FAILED = 'FAILED';
    public const ST_APPROVED = 'APPROVED';
    public const ST_APPROVED_PARTIAL = 'APPROVED_PARTIAL';
    public const ST_REJECTED = 'REJECTED';
    public const ST_PENDING = 'PENDING';
    public const ST_PENDING_VALIDATION = 'PENDING_VALIDATION';
    public const ST_REFUNDED = 'REFUNDED';
    public const ST_ERROR = 'ERROR';
    public const ST_UNKNOWN = 'UNKNOWN';

    protected string $status;
    protected string $reason;
    protected string $message = '';
    protected string $date = '';

    protected static array $STATUSES = [
        self::ST_OK,
        self::ST_FAILED,
        self::ST_APPROVED,
        self::ST_APPROVED_PARTIAL,
        self::ST_REJECTED,
        self::ST_PENDING,
        self::ST_PENDING_VALIDATION,
        self::ST_REFUNDED,
        self::ST_ERROR,
        self::ST_UNKNOWN,
    ];

    public function __construct(array $data)
    {
        $this->load($data, ['status', 'reason', 'message', 'date']);
    }

    public function status(): string
    {
        return $this->status ?? self::ST_ERROR;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function date(): string
    {
        return $this->date;
    }

    public function isSuccessful(): bool
    {
        return $this->status() == self::ST_OK;
    }

    public function isApproved(): bool
    {
        return $this->status() == self::ST_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status() == self::ST_REJECTED;
    }

    public function isError(): bool
    {
        return $this->status() == self::ST_ERROR;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status(),
            'reason' => $this->reason(),
            'message' => $this->message(),
            'date' => $this->date(),
        ];
    }

    public static function quick(string $status, string $reason, string $message = '', string $date = ''): self
    {
        return new self([
            'status' => $status,
            'reason' => $reason,
            'message' => $message,
            'date' => $date ?: date('c'),
        ]);
    }
}
