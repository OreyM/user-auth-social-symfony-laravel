<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Objects\Email;
use Twig\Environment;

class ConfirmTokenSender
{
    private \Swift_Mailer $mailer;
    private Environment $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\LoaderError
     */
    public function send(Email $email, string $token): void
    {
        $message = (new \Swift_Message('SigUp Confirmation'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/signup.html.twig', [
                'token' => $token
            ]), 'text/html');

        if (!$this->mailer->send($message)) throw new \RuntimeException('Unable to send message.');
    }

}