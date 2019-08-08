<?php

namespace App\Repository;

use App\Entity\NameProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NameProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method NameProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method NameProduct[]    findAll()
 * @method NameProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NameProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NameProduct::class);
    }

    // /**
    //  * @return NameProduct[] Returns an array of NameProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NameProduct
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
