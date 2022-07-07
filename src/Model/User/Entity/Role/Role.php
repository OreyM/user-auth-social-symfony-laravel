<?php

namespace App\Model\User\Entity\Role;

use Webmozart\Assert\Assert;

class Role
{
    private const USER = 'user';
    private const ADMIN = 'admin';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN,
        ]);

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function isEqual(self $role): bool
    {
        return $this->getName() === $role->getName();
    }
}