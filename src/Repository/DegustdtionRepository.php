<?php

namespace App\Repository;

use App\Entity\Degustdtion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Degustdtion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Degustdtion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Degustdtion[]    findAll()
 * @method Degustdtion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DegustdtionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Degustdtion::class);
    }

    // /**
    //  * @return Degustdtion[] Returns an array of Degustdtion objects
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
    public function findOneBySomeField($value): ?Degustdtion
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
