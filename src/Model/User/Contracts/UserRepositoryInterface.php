<?php

namespace App\Model\User\Contracts;


use App\Model\User\Entity\User\Objects\Email;
use App\Model\User\Entity\User\Objects\Id;
use App\Model\User\Entity\User\User;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function get(Id $id): User;

    public function hasByEmail(Email $email): bool;

    public function getByEmail(Email $email): User;

    public function hasByNetworkIdentity(string $network, string $identity): bool;

    public function findByConfirmToken(string $token): ?User;

    public function findByResetToken(string $token): ?User;
}