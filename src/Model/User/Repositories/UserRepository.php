<?php

namespace App\Model\User\Repositories;


use App\Exceptions\EntityNotFoundException;
use App\Model\User\Contracts\UserRepositoryInterface;
use App\Model\User\Entity\User\Objects\Email;
use App\Model\User\Entity\User\Objects\Id;
use App\Model\User\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function get(Id $id): User
    {
        /** @var User $user */
        if (!$user = $this->repository->find($id->getValue())) {
            throw new EntityNotFoundException('User not found.');
        }

        return $user;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->repository->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter(':email', $email->getValue())
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function getByEmail(Email $email): User
    {
        /** @var User $user */
        if (!$user = $this->repository->findOneBy(['email' => $email->getValue()])) {
            throw new EntityNotFoundException('User not found.');
        }

        return $user;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasByNetworkIdentity(string $network, string $identity): bool
    {
        return $this->repository->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->innerJoin('t.networks', 'n')
            ->andWhere('n.network = :network AND n.identity = :identity')
            ->setParameter(':network', $network)
            ->setParameter(':identity', $identity)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function findByConfirmToken(string $token): ?User
    {
        return $this->repository->findOneBy(['confirmToken' => $token]);
    }

    public function findByResetToken(string $token): ?User
    {
        return $this->repository->findOneBy(['resetToken.token' => $token]);
    }
}