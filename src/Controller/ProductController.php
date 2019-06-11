<?php

namespace App\Controller;

use App\Entity\Component;
use App\Entity\Product;
use App\Entity\Project;
use App\Entity\Ttk;
use App\Entity\TtkComponent;
use App\Repository\CategoryRepository;
use App\Repository\ComponentRepository;
use App\Repository\MeasureRepository;
use App\Repository\ProductRepository;
use App\Repository\ProjectRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TtkComponentRepository;
use App\Repository\TtkRepository;
use App\Utility\FirstProduct;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/ajax/{project_id}/product/add", name="add_product_ajax")
     */
    public function newProductAjax(ProjectRepository $projectRepository, $project_id, Request $request, CategoryRepository $categoryRepository)
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $product = new Product();
        $entityManager = $this->getDoctrine()->getManager();

        $status = $request->query->get('status');
        $category_id = $request->query->get('category');
        $category = $categoryRepository->findOneBy(['id' => $category_id]);

        $product->setNameWork("Новое блюдо");
        $product->setProject($project);
        //$product->setStatus($status);
        $product->setOldStatus(2);
        $product->setCategory($category);


        $entityManager->persist($product);

        $entityManager->flush();

        ;
        $response_arr = array('id'=>$product->getId());
        $json_respoonse = json_encode($response_arr);
        $response = JsonResponse::fromJsonString($json_respoonse);
        return $response;
    }
    /**
     * @Route("/ajax/{id}/product/update", name="update_product_ajax")
     */
    public function updateProductTtkAjax(Project $project,
                                         ProjectRepository $projectRepository,
                                         CategoryRepository $categoryRepository,
                                         ComponentRepository $componentRepository,
                                         SubcategoryRepository $subcategoryRepository,
                                         MeasureRepository $measureRepository,
                                         ProductRepository $productRepository,
                                         TtkComponentRepository $ttkComponentRepository,
                                         TtkRepository $ttkRepository,
                                         Request $request)
    {


        $json = $request->query->get('product');
        $product_info = json_decode($json);

        $entityManager = $this->getDoctrine()->getManager();


        $product = $productRepository->findOneBy(['id'=>$product_info->id]);

        $category = $categoryRepository->findOneBy(['id'=>$product_info->selected_category]);

        $subcategory = $subcategoryRepository->findOneBy(['id'=>$product_info->selected_subcategory]);

        $product->setNameWork($product_info->name);
        $product->setCategory($category);
        $product->setSubcategory($subcategory);

        $ttk = $ttkRepository->findOneBy(['product'=>$product]);

        if (!$ttk){
            $ttk = new Ttk();
            $product->addTtk($ttk);
        }

        $ttk->setNumber($product_info->ttk_num);
        $ttk->setComment($product_info->comment);
        $ttk->setTechnology($product_info->technology);

        $ttkCompoonents = $ttk->getTtkComponents();
        foreach ($ttkCompoonents as $ttkCompoonent) {
            $entityManager->remove($ttkCompoonent);
        }

        $entityManager->flush();



        $components = $product_info->components;

        foreach ($components as $component_item){
            $component_id = $component_item->component_id;
            $measure_id  = $component_item->measure;
            $count = $component_item->count;

            $component = $componentRepository->findOneBy(['id'=>$component_id]);
            if (!$component){
                $component = new Component();
                $entityManager->persist($component);

            }
            $measure = $measureRepository->findOneBy(['id'=>$measure_id]);

            $ttkComponent = $ttkComponentRepository->findOneBy(['Ttk'=>$ttk,'component'=>$component,'measure'=>$measure]);

            if ($ttkComponent){
                $ttkComponent->setCount($count);

                $entityManager->persist($ttkComponent);
            } else {
                $ttkComponent = new TtkComponent();
                $ttkComponent->setCount($count);
                $ttkComponent->setTtk($ttk);
                $ttkComponent->setComponent($component);
                $ttkComponent->setMeasure($measure);

                $entityManager->persist($ttkComponent);

            }
        }

        $entityManager->persist($ttk);

        $entityManager->flush();

        $entityManager->persist($product);

        return new Response('1');
    }
    /**
     * @Route("/ajax/{id}/product/delete/", name="product_delet", methods={"DELETE"})
     */
    public function delete(Project $project,
                           Request $request,
                           ProductRepository $productRepository,
FirstProduct $firstProduct)
    {
        $json = $request->query->get('product');
        $product_info = json_decode($json);

        $entityManager = $this->getDoctrine()->getManager();

        $product = $productRepository->findOneBy(['id'=>$product_info->id]);

        $product->setOldStatus(0);


        $entityManager->persist($product);

        $entityManager->flush();

        $category = $product->getCategory();

        $response_arr = $firstProduct->getFirstProduct($category);
        $json_respoonse = json_encode($response_arr);
        $response = JsonResponse::fromJsonString($json_respoonse);

        return $response;
    }

}
