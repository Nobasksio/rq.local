<?php

namespace App\Repository;

use App\Entity\IikoTtkComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IikoTtkComponent|null find($id, $lockMode = null, $lockVersion = null)
 * @method IikoTtkComponent|null findOneBy(array $criteria, array $orderBy = null)
 * @method IikoTtkComponent[]    findAll()
 * @method IikoTtkComponent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IikoTtkComponentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IikoTtkComponent::class);
    }

    // /**
    //  * @return IikoTtkComponent[] Returns an array of IikoTtkComponent objects
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
    public function findOneBySomeField($value): ?IikoTtkComponent
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
