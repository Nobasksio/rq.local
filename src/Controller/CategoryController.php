<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 23/04/2019
 * Time: 10:45
 */

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Subcategory;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Utility\FirstProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectRepository;

//todo сделать проверку прав
class CategoryController extends AbstractController
{
    /**
     * @Route("/ajax/{project_id}/inginiring/category/new", name="new_cat_ajax", methods={"GET"})
     */
    public function new($project_id, ProjectRepository $projectRepository, CategoryRepository $categoryRepository, Request $request){

        $category_name = $request->query->get('category_name');

        $project = $projectRepository->findOneBy(['id' => $project_id]);

        $category = new Category();

        $category->setName($category_name);
        $category->setProjectId($project);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        $id = $category->getId();

        return new Response($id);

    }
    /**
     * @Route("/ajax/{project_id}/inginiring/category/{category_id}/hide", name="hide_cat_ajax", methods={"GET"})
     */
    public function hide($project_id,$category_id, ProjectRepository $projectRepository, CategoryRepository $categoryRepository, Request $request){

        $entityManager = $this->getDoctrine()->getManager();
        $category = $categoryRepository->findOneBy(['id' => $category_id]);
        if ($category->getStatus()){
            $status = false;
            $string_status = 0;
        } else {
            $status = true;
            $string_status = 1;
        }
        $category->setStatus($status);
        $entityManager->persist($category);
        $entityManager->flush();

        return new Response($string_status);

    }
    /**
     * @Route("/ajax/{project_id}/inginiring/category/{category_id}/update_type", name="update_cat_type_ajax", methods={"GET"})
     */
    public function updateType($project_id,$category_id, CategoryRepository $categoryRepository, Request $request){
        $new_type = $request->query->get('new_type');
        $category = $categoryRepository->findOneBy(['id' => $category_id]);

        $category->setType($new_type);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return new Response($new_type);
    }
    /**
     * @Route("/ajax/{project_id}/inginiring/category/{category_id}/update_name", name="update_cat_name_ajax", methods={"GET"})
     */
    public function updateName($project_id,$category_id, CategoryRepository $categoryRepository, Request $request){
        $new_name= $request->query->get('new_name');
        $category = $categoryRepository->findOneBy(['id' => $category_id]);

        $category->setname($new_name);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return new Response(1);
    }

    /**
     * @Route("ajax/{id}/category/{category_id}/all_product", name="all_product", methods={"GET","POST"})
     */
    public function show(Project $project,
                         $category_id,
                         FirstProduct $firstProduct,
                         ProductRepository $productRepository,
                         CategoryRepository $categoryRepository): Response
    {
        $Category = $categoryRepository->findOneBy(['id'=>$category_id]);
        $products = $productRepository->findBy(['category' => $Category, 'old_status' => 2]);
        $products_array =[];
        foreach ($products as $product_item){
            $products_array[] = $firstProduct->getProductInfoArray($product_item);
        }

        $app_state = json_encode([
            'products' => $products_array,
        ]);

        $response = JsonResponse::fromJsonString($app_state);

        return $response;
    }

    /**
     * @Route("/ajax/{id}/category/create")
     */

    public function categoryCreateAjax(Project $project, Request $request){

        $category_name = $request->query->get('name');

        $new_category = new Category();

        $new_category->setName($category_name);
        $new_category->setProjectId($project);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($new_category);
        $entityManager->flush();

        $category_id = $new_category->getId();

        return new Response($category_id);

    }
    /**
     * @Route("/ajax/{id}/subcategory/create")
     */

    public function subcategoryCreateAjax(Project $project, Request $request,ProductRepository $productRepository){

        $subcategory_name = $request->query->get('name');
        $product_id = $request->query->get('product_id');

        $product = $productRepository->findOneBy(['id'=>$product_id]);

        $new_subcategory = new Subcategory();

        $new_subcategory->setName($subcategory_name);
        $new_subcategory->setProject($project);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($new_subcategory);
        $entityManager->flush();

        $subcategory_id = $new_subcategory->getId();

        $product->setSubcategory($new_subcategory);
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response($subcategory_id);

    }

}