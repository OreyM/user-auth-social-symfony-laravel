<?php

namespace App\Model\User\UseCase\Network\Auth;

use App\Model\User\Entity\User\Objects\Email;
use App\Model\User\Entity\User\Objects\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Helpers\Flusher;
use App\Model\User\Repositories\UserRepository;

class Handler
{
    private UserRepository $users;
    private Flusher $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function handle(Command $command): void
    {
        if ($this->users->hasByNetworkIdentity($command->network, $command->identity)) {
            throw new \DomainException('User already exists.');
        }

        $user = User::signUpByNetwork(
            Id::next(),
            new \DateTimeImmutable(),
            $command->network,
            $command->identity
        );

        $this->users->add($user);

        $this->flusher->flush();
    }
}