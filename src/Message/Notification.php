<?php


namespace Dnetix\Redirection\Message;


use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Traits\LoaderTrait;
use Dnetix\Redirection\Traits\StatusTrait;

class Notification
{
    use LoaderTrait, StatusTrait;

    protected $requestId;
    protected $reference;
    protected $signature;

    private $tranKey;

    public function __construct($data = [], $tranKey)
    {
        $this->load($data, ['requestId', 'reference', 'signature']);
        $this->setStatus($data['status']);

        $this->tranKey = $tranKey;
    }

    public function requestId()
    {
        return $this->requestId;
    }

    public function reference()
    {
        return $this->reference;
    }

    public function signature()
    {
        return $this->signature;
    }

    public function makeSignature()
    {
        return sha1($this->requestId() . $this->status()->status() . $this->status()->date() . $this->tranKey);
    }

    public function isValidNotification()
    {
        return $this->signature() == $this->makeSignature();
    }

    public function isApproved()
    {
        return $this->status()->status() == Status::ST_APPROVED;
    }

    public function isRejected()
    {
        return $this->status()->status() == Status::ST_REJECTED;
    }

}