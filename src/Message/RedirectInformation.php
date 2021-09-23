<?php

namespace Dnetix\Redirection\Message;

use Dnetix\Redirection\Contracts\Entity;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Entities\SubscriptionInformation;
use Dnetix\Redirection\Entities\Transaction;
use Dnetix\Redirection\Traits\StatusTrait;

class RedirectInformation extends Entity
{
    use StatusTrait;

    protected string $requestId;
    protected ?RedirectRequest $request = null;
    /**
     * @var Transaction[]
     */
    protected array $payment = [];
    protected ?SubscriptionInformation $subscription = null;

    public function __construct(array $data = [])
    {
        $this->requestId = $data['requestId'] ?? '';
        $this->loadEntity($data['status'], 'status', Status::class);
        $this->loadEntity($data['request'] ?? null, 'request', RedirectRequest::class);

        if (isset($data['payment'])) {
            $this->setPayment($data['payment']);
        }

        if (isset($data['subscription'])) {
            $this->setSubscription($data['subscription']);
        }
    }

    public function requestId(): string
    {
        return $this->requestId;
    }

    public function request(): ?RedirectRequest
    {
        return $this->request;
    }

    /**
     * @return Transaction[]
     */
    public function payment(): array
    {
        return $this->payment;
    }

    public function subscription(): ?SubscriptionInformation
    {
        return $this->subscription;
    }

    public function setPayment($payments): self
    {
        if ($payments) {
            $this->payment = [];

            if (isset($payments['transaction']) && $payments['transaction']) {
                $payments = $payments['transaction'];
            }

            foreach ($payments as $payment) {
                $this->payment[] = new Transaction($payment);
            }
        }
        return $this;
    }

    public function setSubscription($subscription): self
    {
        $this->loadEntity($subscription, 'subscription', SubscriptionInformation::class);
        return $this;
    }

    private function paymentToArray(): array
    {
        if (!$this->payment() || !is_array($this->payment())) {
            return [];
        }

        $payments = [];
        foreach ($this->payment() as $payment) {
            $payments[] = $payment->toArray();
        }
        return $payments ?: [];
    }

    public function lastApprovedTransaction(): ?Transaction
    {
        return $this->lastTransaction(true);
    }

    /**
     * Obtains the last transaction made to the session.
     * @param bool $approved
     * @return Transaction
     */
    public function lastTransaction(bool $approved = false): ?Transaction
    {
        $transactions = $this->payment();
        if (is_array($transactions) && count($transactions) > 0) {
            if ($approved) {
                while ($transaction = array_shift($transactions)) {
                    if ($transaction->isApproved()) {
                        return $transaction;
                    }
                }
            } else {
                return $transactions[0];
            }
        }
        return null;
    }

    /**
     * Returns the last authorization associated with the session.
     */
    public function lastAuthorization(): string
    {
        if ($this->lastApprovedTransaction()) {
            return $this->lastApprovedTransaction()->authorization();
        }
        return '';
    }

    public function toArray(): array
    {
        return $this->arrayFilter([
            'requestId' => $this->requestId(),
            'status' => $this->status()->toArray(),
            'request' => $this->request() ? $this->request()->toArray() : null,
            'payment' => $this->paymentToArray(),
            'subscription' => $this->subscription() ? $this->subscription()->toArray() : null,
        ]);
    }
}
