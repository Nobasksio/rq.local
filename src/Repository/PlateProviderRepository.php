<?php

namespace App\Repository;

use App\Entity\PlateProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlateProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlateProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlateProvider[]    findAll()
 * @method PlateProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlateProviderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlateProvider::class);
    }

    // /**
    //  * @return PlateProvider[] Returns an array of PlateProvider objects
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
    public function findOneBySomeField($value): ?PlateProvider
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
