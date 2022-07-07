<?php

namespace App\Model\User\Security;

use App\Model\User\Entity\User\Objects\Status;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentity implements UserInterface, EquatableInterface
{
    private string $id;
    private string $username;
    private string $password;
    private string $role;
    private string $status;

    public function __construct(
        string $id,
        string $username,
        string $password,
        string $role,
        string $status
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function isActive(): bool
    {
        return $this->status === Status::active();
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        return
            $this->id === $user->id &&
            $this->username === $user->username &&
            $this->password === $user->password &&
            $this->role === $user->role &&
            $this->status === $user->status;
    }

    public function eraseCredentials(): void
    {
        // TODO
    }
}
