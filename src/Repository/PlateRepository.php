<?php

namespace App\Repository;

use App\Entity\Plate;
use App\Entity\Product;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Plate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plate[]    findAll()
 * @method Plate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Plate::class);
    }

//    public function findByProject(Project $project)
//    {
//        return $this->createQueryBuilder('p')
//            ->from('plate');
//            ->andWhere('p.project = :val')
//            ->setParameter('val', $project->getId())
//            ->getQuery()
//            ->getResult()
//            ;
//    }

    // /**
    //  * @return Plate[] Returns an array of Plate objects
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
    public function findOneBySomeField($value): ?Plate
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
