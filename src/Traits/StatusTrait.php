<?php


namespace Dnetix\Redirection\Traits;


use Dnetix\Redirection\Entities\Status;

trait StatusTrait
{
    /**
     * @var Status
     */
    protected $status;

    /**
     * @return Status
     */
    public function status()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if (is_array($status))
            $status = new Status($status);
        $this->status = $status;
        return $this;
    }

}