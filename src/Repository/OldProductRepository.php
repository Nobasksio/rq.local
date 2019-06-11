<?php

namespace App\Repository;

use App\Entity\OldProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OldProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OldProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OldProduct[]    findAll()
 * @method OldProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OldProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OldProduct::class);
    }

    // /**
    //  * @return OldProduct[] Returns an array of OldProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OldProduct
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
