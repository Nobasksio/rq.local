<?php

namespace App\Repository;

use App\Entity\UserProjectRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserProjectRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProjectRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProjectRole[]    findAll()
 * @method UserProjectRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProjectRoleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserProjectRole::class);
    }

    // /**
    //  * @return UserProjectRole[] Returns an array of UserProjectRole objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserProjectRole
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
