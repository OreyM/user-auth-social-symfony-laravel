<?php

namespace App\Model\User\Entity\User;

use App\Model\User\Entity\Role\Role;
use App\Model\User\Entity\User\Objects\Email;
use App\Model\User\Entity\User\Objects\Id;
use App\Model\User\Entity\User\Objects\Status;
use App\Model\User\Entity\User\Traits\ConfirmTrait;
use App\Model\User\Entity\User\Traits\ResetPasswordTrait;
use App\Model\User\Entity\User\Traits\UserConstructorTrait;
use App\Model\User\Entity\User\Traits\UserDoctrineTrait;
use App\Model\User\Entity\User\Traits\UserFactoryTrait;
use App\Model\User\Entity\User\Traits\UserRoleTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 *     @ORM\UniqueConstraint(columns={"reset_token_token"})
 * })
 */
class User
{
    use UserDoctrineTrait;
    use UserConstructorTrait;
    use ResetPasswordTrait;
    use ConfirmTrait;
    use UserRoleTrait;

    private function __construct(
        Id                 $id,
        \DateTimeImmutable $date
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->networks = new ArrayCollection();
        $this->role = Role::user();
        $this->status = Status::wait();

        $this->email = null;
        $this->resetToken = null;
    }

    public function isWait(): bool
    {
        return $this->status === Status::wait();
    }

    public function isActive(): bool
    {
        return $this->status === Status::active();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setEmail(string $email): void
    {
        $this->email = new Email($email);
    }
}