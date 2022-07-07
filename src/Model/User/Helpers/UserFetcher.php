<?php

namespace App\Model\User\Helpers;


use Doctrine\DBAL\Connection;

class UserFetcher
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $token
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function existsByResetToken(string $token): bool
    {
        return $this->connection->createQueryBuilder()
            ->select('COUNT (*)')
            ->from('user_users')
            ->where("reset_token_token = '$token'")
            ->execute()
            ->fetchFirstColumn()[0] > 0;
    }

    /**
     * @param string $email
     * @return AuthView|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function findForAuthByEmail(string $email): ?AuthView
    {
        $statement = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'password_hash',
                'role',
                'status'
            )
            ->from('user_users')
            ->where("email = '$email'")
            ->execute();

        $result = $statement->fetch();

        return $result ? (new AuthView())->fetchData($result) : null;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findForAuthByNetwork(string $network, string $identity): ?AuthView
    {
        $statement = $this->connection->createQueryBuilder()
            ->select(
                'u.id',
                'u.email',
                'u.password_hash',
                'u.role',
                'u.status'
            )
            ->from('user_users', 'u')
            ->innerJoin('u', 'user_user_networks', 'n', 'n.user_id = u.id')
            ->where("n.network = '$network' AND n.identity = '$identity'")
            ->execute();

        $result = $statement->fetch();

        return $result ? (new AuthView())->fetchData($result) : null;
    }
}
