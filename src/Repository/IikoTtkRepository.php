<?php

namespace App\Repository;

use App\Entity\IikoTtk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IikoTtk|null find($id, $lockMode = null, $lockVersion = null)
 * @method IikoTtk|null findOneBy(array $criteria, array $orderBy = null)
 * @method IikoTtk[]    findAll()
 * @method IikoTtk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IikoTtkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IikoTtk::class);
    }



    // /**
    //  * @return IikoTtk[] Returns an array of IikoTtk objects
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
    public function findOneBySomeField($value): ?IikoTtk
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
