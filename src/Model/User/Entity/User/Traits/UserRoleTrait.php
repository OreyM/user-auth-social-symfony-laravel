<?php

namespace App\Model\User\Entity\User\Traits;

use App\Model\User\Entity\Role\Role;

trait UserRoleTrait
{
    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('The user has already been assigned this role.');
        }

        $this->role = $role;
    }
}