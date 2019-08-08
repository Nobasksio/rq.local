<?php

namespace App\Repository;

use App\Entity\PlatePaternMatrix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlatePaternMatrix|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlatePaternMatrix|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlatePaternMatrix[]    findAll()
 * @method PlatePaternMatrix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatePaternMatrixRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlatePaternMatrix::class);
    }

    // /**
    //  * @return PlatePaternMatrix[] Returns an array of PlatePaternMatrix objects
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
    public function findOneBySomeField($value): ?PlatePaternMatrix
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
