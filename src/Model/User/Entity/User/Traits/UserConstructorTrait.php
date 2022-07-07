<?php

namespace App\Model\User\Entity\User\Traits;

use App\Model\User\Entity\Network\Network;
use App\Model\User\Entity\User\Objects\Email;
use App\Model\User\Entity\User\Objects\Id;
use App\Model\User\Entity\User\Objects\Status;

trait UserConstructorTrait
{
    public static function signUpByEmail(
        Id                 $id,
        \DateTimeImmutable $date,
        Email              $email,
        string             $passwordHash,
        string             $token
    ): self
    {
        $user = new self($id, $date);

        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->confirmToken = $token;

        return $user;
    }

    public static function signUpByNetwork(
        Id                 $id,
        \DateTimeImmutable $date,
        string             $network,
        string             $identity
    ): self
    {
        $user = new self($id, $date);

        $user->attachNetwork($network, $identity);
        $user->status = Status::active();

        return $user;
    }

    private function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) throw new \DomainException('Network already attached.');
        }

        $this->networks->add(new Network($this, $network, $identity));
    }
}