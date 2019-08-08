<?php

namespace App\Repository;

use App\Entity\DescriptionMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DescriptionMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method DescriptionMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method DescriptionMenu[]    findAll()
 * @method DescriptionMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DescriptionMenuRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DescriptionMenu::class);
    }

    // /**
    //  * @return DescriptionMenu[] Returns an array of DescriptionMenu objects
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
    public function findOneBySomeField($value): ?DescriptionMenu
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
