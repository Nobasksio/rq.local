<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findOldProject(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM old_project p
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function findOldAnalitic(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM rq_menu p
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function findOldCat(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM rq_cat p
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function findOldProduct(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM rq_tov p
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
    public function findOldnewCategory(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM nm_cat p
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
    public function findOldnewProduct(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM rq_nm_tov p
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function findOldProductDegustation($old_degust_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT * FROM rq_nm_tov p where id_tasting = $old_degust_id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function findOldRating(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM rq_tasting_rating p
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function findOldDegustation(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM rq_tasting t
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
