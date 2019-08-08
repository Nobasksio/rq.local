<?php

namespace App\Repository;

use App\Entity\PlateMatrix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlateMatrix|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlateMatrix|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlateMatrix[]    findAll()
 * @method PlateMatrix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlateMatrixRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlateMatrix::class);
    }

    // /**
    //  * @return PlateMatrix[] Returns an array of PlateMatrix objects
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
    public function findOneBySomeField($value): ?PlateMatrix
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
