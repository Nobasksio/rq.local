<?php

namespace App\Repository;

use App\Entity\DegustationScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DegustationScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method DegustationScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method DegustationScore[]    findAll()
 * @method DegustationScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DegustationScoreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DegustationScore::class);
    }

    // /**
    //  * @return DegustationScore[] Returns an array of DegustationScore objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DegustationScore
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
