<?php

namespace App\Model\User\Entity\User\Traits;

use App\Model\User\Entity\User\Objects\Status;

trait ConfirmTrait
{
    public function confirmSignUp()
    {
        if (!$this->isWait()) {
            throw new \DomainException('User already confirmed.');
        }

        $this->status = Status::active();
        $this->confirmToken = null;
    }
}