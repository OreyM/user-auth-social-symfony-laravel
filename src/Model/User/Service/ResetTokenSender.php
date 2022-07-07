<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\Token\ResetToken;
use App\Model\User\Entity\User\Objects\Email;
use Twig\Environment;

class ResetTokenSender
{
    private \Swift_Mailer $mailer;
    private Environment $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(Email $email, ResetToken $token): void
    {
        $message = (new \Swift_Message('Password resetting'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/reset.html.twig', [
                'token' => $token->getToken()
            ]), 'text/html');

        if (!$this->mailer->send($message)) throw new \RuntimeException('Unable to send message.');
    }

}