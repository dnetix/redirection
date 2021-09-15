<?php

namespace Dnetix\Redirection\Entities;

use Dnetix\Redirection\Contracts\Entity;

class Instrument extends Entity
{
    protected ?Account $bank = null;
    protected ?Token $token = null;
    protected ?Credit $credit = null;
    protected string $pin;
    protected string $password;

    public function __construct($data = [])
    {
        $this->loadEntity($data['bank'] ?? null, 'bank', Account::class);
        $this->loadEntity($data['credit'] ?? null, 'credit', Credit::class);
        $this->loadEntity($data['token'] ?? null, 'token', Token::class);

        $this->pin = $data['pin'] ?? '';
        $this->password = $data['password'] ?? '';
    }

    public function bank(): ?Account
    {
        return $this->bank;
    }

    public function credit(): ?Credit
    {
        return $this->credit;
    }

    public function token(): ?Token
    {
        return $this->token;
    }

    public function pin(): string
    {
        return $this->pin;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'bank' => $this->bank() ? $this->bank()->toArray() : null,
            'credit' => $this->credit() ? $this->credit()->toArray() : null,
            'token' => $this->token() ? $this->token()->toArray() : null,
            'pin' => $this->pin(),
            'password' => $this->password(),
        ]);
    }
}
