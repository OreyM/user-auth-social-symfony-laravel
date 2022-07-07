<?php

namespace App\Model\User\UseCase\Reset\Request;

use App\Model\User\Entity\User\Objects\Email;
use App\Model\User\Helpers\Flusher;
use App\Model\User\Repositories\UserRepository;
use App\Model\User\Service\ResetTokenizer;
use App\Model\User\Service\ResetTokenSender;

class Handler
{
    private UserRepository $users;
    private ResetTokenizer $tokenizer;
    private Flusher $flusher;
    private ResetTokenSender $sender;

    public function __construct(
        UserRepository $users,
        ResetTokenizer $tokenizer,
        Flusher $flusher,
        ResetTokenSender $sender
    )
    {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->tokenizer->generate(),
            new \DateTimeImmutable()
        );

        $this->flusher->flush();

        $this->sender->send($user->getEmail(), $user->getResetToken());
    }
}