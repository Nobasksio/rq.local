<?php

namespace App\Repository;

use App\Entity\DownloadAnaliticSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DownloadAnaliticSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method DownloadAnaliticSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method DownloadAnaliticSetting[]    findAll()
 * @method DownloadAnaliticSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadAnaliticSettingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DownloadAnaliticSetting::class);
    }

    // /**
    //  * @return DownloadAnaliticSetting[] Returns an array of DownloadAnaliticSetting objects
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
    public function findOneBySomeField($value): ?DownloadAnaliticSetting
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
