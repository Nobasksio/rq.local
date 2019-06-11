<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 17/04/2019
 * Time: 15:32
 */

namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\OldCategoryRepository;
use App\Repository\OldProductRepository;
use App\Repository\ProductRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InginiringController extends AbstractController
{
    /**
     * @Route("/project/{project_id}/inginiring", name="inginiring", methods={"GET"})
     */

    public function index($project_id, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        return $this->render('inginiring/index.html.twig', [
            'project' => $project
        ]);
    }

    /**
     * @Route("/project/{project_id}/inginiring/category", name="ing_cat", methods={"GET"})
     */
    public function ingCat($project_id, ProjectRepository $projectRepository,CategoryRepository $categoryRepository){
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categories = $categoryRepository->findBy(['project' => $project,'status'=>true]);


        return $this->render('inginiring/category.html.twig', [
            'project' => $project,
            'categories' =>$categories
        ]);
    }

    /**
     * @Route("/project/{project_id}/inginiring/count_dish", name="count_dish", methods={"GET"})
     */
    public function countDish($project_id,OldProductRepository $oldProductRepository, ProjectRepository $projectRepository,CategoryRepository $categoryRepository,ProductRepository $productRepository){

        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categories = $categoryRepository->findBy(['project' => $project,'status'=>true]);

        foreach ($categories as $id=>$category){
            $moved_products = $productRepository->findMovedProduct($category);
            $category->setMovedAmount(count($moved_products));
            $old_category = $category->getOldCategory();
            if ($old_category!=0){

                $old_products = $oldProductRepository->findBy(['category'=>$category->getOldCategory()]);
                $category->setOldAmount(count($old_products));
            }
            else {
                $category->setOldAmount(0);
            }

            $categories[$id] = $category;
        }

        return $this->render('inginiring/count_dish.html.twig', [
            'project' => $project,
            'categories' =>$categories
        ]);
    }
    /**
     * @Route("/ajax/project/{project_id}/inginiring/count_dish", name="ajax_save_count", methods={"GET"})
     */
    public function ajaxSaveAmount(CategoryRepository $categoryRepository, Request $request){
        $arr_kol = $request->query->get('arr_kol');
        $arr_cat = explode("}", $arr_kol);
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($arr_cat as $one_cat_str){

            $one_cat_arr = explode(":", $one_cat_str);

            if (!isset($one_cat_arr[1])){
                break;
            }
            $category_id = $one_cat_arr[0];
            $amount_plan = $one_cat_arr[1];

            $category = $categoryRepository->findOneBy(['id' => $category_id]);

            $category->setAmountPlan($amount_plan);

            $entityManager->persist($category);

        }

        $entityManager->flush();

        return new Response(1);

    }


    /**
     * @Route("/project/{project_id}/inginiring/price_cost_manage", name="price_cost_manage", methods={"GET"})
     */
    public function priceCostManage($project_id, ProjectRepository $projectRepository,CategoryRepository $categoryRepository){

        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categories = $categoryRepository->findBy(['project' => $project,'status'=>true]);

        return $this->render('inginiring/price_cost_manager.html.twig', [
            'project' => $project,
            'categories' =>$categories,
            'per_ss_menu' => 0
        ]);
    }
    /**
     * @Route("/project/{project_id}/inginiring/price_cat", name="price_cat", methods={"GET"})
     */
    public function priceCat($project_id, ProjectRepository $projectRepository,CategoryRepository $categoryRepository){

        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categories = $categoryRepository->findBy(['project' => $project,'status'=>true]);


        return $this->render('inginiring/category.html.twig', [
            'project' => $project,
            'categories' =>$categories
        ]);
    }
    /**
     * @Route("/project/{project_id}/inginiring/make_tz", name="make_tz", methods={"GET"})
     */
    public function makeTz($project_id, ProjectRepository $projectRepository,CategoryRepository $categoryRepository){

        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categories = $categoryRepository->findBy(['project' => $project,'status'=>true]);


        return $this->render('inginiring/category.html.twig', [
            'project' => $project,
            'categories' =>$categories
        ]);
    }

}