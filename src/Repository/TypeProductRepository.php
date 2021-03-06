<?php

namespace App\Repository;

use App\Entity\TypeProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeProduct[]    findAll()
 * @method TypeProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeProduct::class);
    }

    // /**
    //  * @return TypeProduct[] Returns an array of TypeProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeProduct
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
