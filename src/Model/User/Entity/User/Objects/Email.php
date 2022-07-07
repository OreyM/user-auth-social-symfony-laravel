<?php

namespace App\Model\User\Entity\User\Objects;


use Webmozart\Assert\Assert;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Wrong email.');
        }

        $this->value = mb_strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}