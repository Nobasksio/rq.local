<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 17/04/2019
 * Time: 10:58
 */

namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\OldProductRepository;
use App\Repository\ProductRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class OldProductController extends AbstractController
{
    /**
     * @Route("ajax/move_to_menu/{project_id}/{old_product_id}", name="move_to_menu",requirements={"project_id"="\d+", "old_product_id"="\d+"}, methods={"GET"})
     */
    public function toMenu($project_id, $old_product_id, ProductRepository $productRepository, OldProductRepository $oldProductRepository, ProjectRepository $projectRepository, CategoryRepository $categoryRepository){

        $old_product = $oldProductRepository->findOneBy(['id' => $old_product_id]);
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $product = $productRepository->findOneBy(['project' => $project, 'old_product' => $old_product]);

        $entityManager = $this->getDoctrine()->getManager();

        if (!$product) {

            $work_name = $old_product->getName();
            $product = new Product();

            $product->setNameWork($work_name);
            $product->setOldProduct($old_product);
            $product->setProject($project);

            $old_category = $old_product->getCategory();
            $old_category_id = $old_category->getId();
            $category = $categoryRepository->findOneBy(['old_category' => $old_category_id]);

            if (!$category){
                $category = new Product();
                
            }

            $entityManager->persist($product);
            $old_product->setProduct($product);
            $entityManager->flush();

            return new Response(1);

        } else {

            if ($product->getStatus()){
                $product->setStatus(false);
            } else {
                $product->setStatus(true);
            }

            $entityManager->flush();

            return new Response(0);
        }

    }

}