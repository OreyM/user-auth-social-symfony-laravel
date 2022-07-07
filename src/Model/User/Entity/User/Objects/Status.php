<?php

namespace App\Model\User\Entity\User\Objects;

class Status
{
    private const WAIT = 'wait';
    private const ACTIVE = 'active';

    public static function wait(): string
    {
        return self::WAIT;
    }

    public static function active(): string
    {
        return self::ACTIVE;
    }
}