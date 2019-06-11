<?php

namespace App\Repository;

use App\Entity\TtkComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TtkComponent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TtkComponent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TtkComponent[]    findAll()
 * @method TtkComponent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TtkComponentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TtkComponent::class);
    }

    // /**
    //  * @return TtkComponent[] Returns an array of TtkComponent objects
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
    public function findOneBySomeField($value): ?TtkComponent
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
