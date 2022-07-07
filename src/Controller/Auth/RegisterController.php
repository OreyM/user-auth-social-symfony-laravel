<?php

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterController extends AbstractController
{

    private LoggerInterface $logger;
    private TranslatorInterface $translator;

    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Route("/register", name="auth.register")
     * @param Request $request
     * @param SignUp\Request\Handler $handler
     * @return Response
     */
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        $command = new SignUp\Request\Command();

        $form = $this->createForm(SignUp\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');

                return $this->redirectToRoute('home');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash(
                    'error',
                    $this->translator->trans($e->getMessage(), [], 'exceptions')
                );
            }
        }

        return $this->render('app/pages/auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/{token}", name="auth.register.confirm")
     * @param string $token
     * @param SignUp\Confirm\Handler $handler
     * @return Response
     */
    public function confirm(string $token, SignUp\Confirm\Handler $handler): Response
    {
        $command = new SignUp\Confirm\Command($token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email successfully confirmed.');

            return $this->redirectToRoute('home');
        } catch (\DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash(
                'error',
                $this->translator->trans($e->getMessage(), [], 'exceptions')
            );

            return $this->redirectToRoute('home');
        }
    }

}