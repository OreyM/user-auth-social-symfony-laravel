<?php

namespace App\Controller\Auth;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginSocialController extends AbstractController
{
    /**
     * @Route("/auth/social/{type}", name="auth.social")
     * @param $type
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connect($type, ClientRegistry $clientRegistry): Response
    {
        // TODO make factory
        switch ($type) {
            case 'google':
                $social = 'google_main';
                $param = [
                    'openid',
                    'https://www.googleapis.com/auth/userinfo.email',
                    'https://www.googleapis.com/auth/userinfo.profile',
                ];
                break;
            case 'facebook':
                $social = 'facebook_main';
                $param = ['public_profile', 'email'];
                break;
            default:
                throw new \Exception('Wrong social network.');
        }

        return $clientRegistry->getClient($social)->redirect($param);
    }

    /**
     * @Route("/auth/google/check", name="auth.google_check")
     * @Route("/auth/facebook/check", name="auth.facebook_check")
     * @return Response
     */
    public function check(): Response
    {
        return $this->redirectToRoute('home');
    }
}