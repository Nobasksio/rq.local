<?php

namespace App\Repository;

use App\Entity\IikoCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IikoCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method IikoCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method IikoCategory[]    findAll()
 * @method IikoCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IikoCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IikoCategory::class);
    }

    public function findIikoId($id){
        return $this->createQueryBuilder('i')
            ->select('i.iiko_id')
            ->andWhere('i.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }

    public function findIikoIdByIikoId($iiko_id){
        return $this->createQueryBuilder('i')
            ->select('i.iiko_id')
            ->andWhere('i.parent = :val')
            ->setParameter('val', $iiko_id)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return IikoCategory[] Returns an array of IikoCategory objects
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
    public function findOneBySomeField($value): ?IikoCategory
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
