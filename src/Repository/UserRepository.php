<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($login)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.login = :login OR u.email = :email')
            ->setParameter('login', $login)
            ->setParameter('email', $login)
            ->getQuery();

        $user = $qb->getOneOrNullResult();

        if (!$user instanceof User) {
            throw new UsernameNotFoundException("Pas d'utilisateur trouvé avec : $login");
        }

        return $user;
    }
}
