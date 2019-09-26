<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 03/09/2019
 * Time: 14:14
 */

namespace App\Factory;


use App\Entity\Category;
use App\Entity\OldCategory;
use Doctrine\ORM\EntityManagerInterface;

class CategoryFactory
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function createBasedOldCategory(OldCategory $oldCategory){
        $category = new Category();

        $category->setName($oldCategory->getName());
        $category->setProject($oldCategory->getAnalitics()->getProject());
        $category->setType($oldCategory->getType());
        $category->setOldCategory($oldCategory);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

}