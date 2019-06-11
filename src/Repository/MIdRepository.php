<?php

namespace App\Repository;

use App\Entity\MId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MId|null find($id, $lockMode = null, $lockVersion = null)
 * @method MId|null findOneBy(array $criteria, array $orderBy = null)
 * @method MId[]    findAll()
 * @method MId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MIdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MId::class);
    }

    // /**
    //  * @return MId[] Returns an array of MId objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MId
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
