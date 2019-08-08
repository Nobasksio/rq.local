<?php

namespace App\Repository;

use App\Entity\PlateKol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlateKol|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlateKol|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlateKol[]    findAll()
 * @method PlateKol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlateKolRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlateKol::class);
    }

    // /**
    //  * @return PlateKol[] Returns an array of PlateKol objects
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
    public function findOneBySomeField($value): ?PlateKol
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
