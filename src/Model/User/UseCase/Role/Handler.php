<?php

namespace App\Model\User\UseCase\Role;


use App\Model\User\Entity\Role\Role;
use App\Model\User\Entity\User\Objects\Id;
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

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->changeRole(new Role($command->role));

        $this->flusher->flush();
    }
}