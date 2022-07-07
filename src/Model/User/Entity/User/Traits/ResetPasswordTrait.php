<?php

namespace App\Model\User\Entity\User\Traits;

use App\Model\User\Entity\Token\ResetToken;

trait ResetPasswordTrait
{
    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if (!$this->isActive()) throw new \DomainException('User not active.');

        if (!$this->email) throw new \DomainException('Email not specified.');

        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting already requested.');
        }

        $this->resetToken = $token;
    }

    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) throw new \DomainException('Resetting not requested.');

        if ($this->resetToken->isExpiredTo($date)) throw new \DomainException('Reset token is expired.');

        $this->passwordHash = $hash;

        $this->resetToken = null;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }
}