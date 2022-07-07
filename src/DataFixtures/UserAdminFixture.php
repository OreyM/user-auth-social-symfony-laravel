<?php

namespace App\DataFixtures;


use App\Model\User\Entity\Role\Role;
use App\Model\User\Entity\User\Objects\Email;
use App\Model\User\Entity\User\Objects\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserAdminFixture extends Fixture
{
    private PasswordHasher $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $hash = $this->hasher->hash('secret');

        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            new Email('admin@mail.com'),
            $hash,
            'token'
        );

        $user->confirmSignUp();

        $user->changeRole(Role::admin());

        $manager->persist($user);

        $manager->flush();
    }
}
