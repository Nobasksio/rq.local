<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 03/09/2019
 * Time: 14:00
 */

namespace App\Factory;


use App\Entity\OldProduct;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductFactory
{
    private $categoryRepository;
    private $em;
    private $categoryFactory;

    public function __construct(CategoryRepository $categoryRepository,
                                EntityManagerInterface $em,
                                CategoryFactory $categoryFactory) {
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->categoryFactory = $categoryFactory;
    }

    public function createProduct(){
        $product = new Product();

        return $product;
    }

    public function createBasedOldProduct(OldProduct $oldProduct){
        $product = $this->createProduct();

        $product->setNameWork($oldProduct->getName());
        $product->setOldPrice($oldProduct->getPrice());
        $product->setCostPrice($oldProduct->getcostPrice());
        $product->setProject($oldProduct->getCategory()->getAnalitics()->getProject());

        $category = $this->categoryRepository->findOneBy(['old_category'=>$oldProduct->getCategory()]);

        if ($category){
            $product->setCategory($category);
        } else {

            $category = $this->categoryFactory->createBasedOldCategory($oldProduct->getCategory());

            $product->setCategory($category);
        }

        $product->setOldProduct($oldProduct);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

}