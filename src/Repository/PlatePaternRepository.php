<?php

namespace App\Repository;

use App\Entity\PlatePatern;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlatePatern|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlatePatern|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlatePatern[]    findAll()
 * @method PlatePatern[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatePaternRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlatePatern::class);
    }

    // /**
    //  * @return PlatePatern[] Returns an array of PlatePatern objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlatePatern
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
