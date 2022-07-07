<?php

namespace App\Model\User\Entity\User\Types;

use App\Model\User\Entity\User\Objects\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class EmailType extends StringType
{
    public const NAME = 'user_user_email';

    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Email ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        return !empty($value) ? new Email($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

}