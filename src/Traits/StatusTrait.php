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
        if ($this->status instanceof Status) {
            return $this->status;
        }

        return new Status([
            'status' => Status::ST_ERROR,
            'message' => 'No response status was provisioned',
            'reason' => ''
        ]);
    }
}
