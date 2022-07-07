<?php

namespace App\Model\User\Providers;

use App\Model\User\Helpers\AuthView;
use App\Model\User\Helpers\UserFetcher;
use App\Model\User\Security\UserIdentity;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserFetcher $users;

    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    /**
     * @param $username
     * @return UserInterface
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->loadUser($username);
        return self::identityByUser($user, $username);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if ($user = $this->loadUser($identifier)) throw new UserNotFoundException('User not found.');

        return self::identityByUser($user);
    }

    /**
     * @param UserInterface $identity
     * @return UserInterface
     * @throws \Doctrine\DBAL\Exception
     */
    public function refreshUser(UserInterface $identity): UserInterface
    {
        if (!$identity instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($identity));
        }

        $user = $this->loadUser($identity->getUsername());

        return self::identityByUser($user, $identity->getUsername());

    }

    public function supportsClass($class): bool
    {
        return $class === UserIdentity::class;
    }

    /**
     * @param $username
     * @return AuthView
     * @throws \Doctrine\DBAL\Exception
     */
    private function loadUser($username): AuthView
    {
        $chunks = explode(':', $username);

        if (\count($chunks) === 2 && $user = $this->users->findForAuthByNetwork($chunks[0], $chunks[1])) return $user;

        if ($user = $this->users->findForAuthByEmail($username)) return $user;

        throw new UsernameNotFoundException('');
    }

    private static function identityByUser(AuthView $user, ?string $username = null): UserIdentity
    {
        return new UserIdentity(
            $user->id,
            $username,
            $user->password_hash ?: '',
            $user->role,
            $user->status
        );
    }

}
