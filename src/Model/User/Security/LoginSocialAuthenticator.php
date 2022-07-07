<?php

namespace App\Model\User\Security;


use App\Model\User\UseCase\Network\Auth\Command;
use App\Model\User\UseCase\Network\Auth\Handler;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class LoginSocialAuthenticator extends SocialAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;
    private ClientRegistry $clients;
    private Handler $handler;
    private string $client;
    private string $network;

    public function __construct(UrlGeneratorInterface $urlGenerator, ClientRegistry $clients, Handler $handler)
    {
        $this->urlGenerator = $urlGenerator;
        $this->clients = $clients;
        $this->handler = $handler;
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'auth.google_check' ||
            $request->attributes->get('_route') === 'auth.facebook_check';
    }

    public function getCredentials(Request $request)
    {
        // TODO factory
        switch ($request->attributes->get('_route')) {
            case 'auth.facebook_check':
                $this->client = 'facebook_main';
                $this->network = 'facebook';
                return $this->fetchAccessToken($this->getClient());
            case 'auth.google_check':
                $this->client = 'google_main';
                $this->network = 'google';
                return $this->fetchAccessToken($this->getClient());
        }
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $user = $this->getClient()->fetchUserFromToken($credentials);
        $id = $user->getId();
        $username = $this->network . ':' . $id;

        try {
            return $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            $this->handler->handle(new Command($this->network, $id));

            return $userProvider->loadUserByUsername($username);
        }
    }

    /**
     * @return GoogleClient|FacebookClient|OAuth2Client
     */
    private function getClient(): OAuth2Client
    {
        return $this->clients->getClient($this->client);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}
