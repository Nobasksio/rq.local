<?php

namespace App\Repository;

use App\Entity\NameMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NameMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method NameMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method NameMenu[]    findAll()
 * @method NameMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NameMenuRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NameMenu::class);
    }

    // /**
    //  * @return NameMenu[] Returns an array of NameMenu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NameMenu
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
