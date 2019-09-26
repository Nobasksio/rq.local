<?php

namespace App\Repository;

use App\Entity\IikoProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IikoProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method IikoProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method IikoProduct[]    findAll()
 * @method IikoProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IikoProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IikoProduct::class);
    }

    public function findIikoIdByCategories($categoies_id){

        return $this->createQueryBuilder('i')
            ->select('i.iiko_id')
            ->andWhere('i.parent = :val')
            ->setParameter('val', $categoies_id)
            ->getQuery()
            ->getArrayResult();
    }

    // /**
    //  * @return IikoProduct[] Returns an array of IikoProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IikoProduct
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
