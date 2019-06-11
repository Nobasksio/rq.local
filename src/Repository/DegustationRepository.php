<?php

namespace App\Repository;

use App\Entity\Degustation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Degustation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Degustation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Degustation[]    findAll()
 * @method Degustation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DegustationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Degustation::class);
    }

    // /**
    //  * @return Degustation[] Returns an array of Degustation objects
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
    public function findOneBySomeField($value): ?Degustation
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
