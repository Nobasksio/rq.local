<?php

namespace App\Controller;

use App\Entity\Component;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\Project;
use App\Entity\Ttk;
use App\Entity\TtkComponent;
use App\Repository\CategoryRepository;
use App\Repository\ComponentRepository;
use App\Repository\IikoProductRepository;
use App\Repository\IikoTtkComponentRepository;
use App\Repository\IikoTtkRepository;
use App\Repository\MeasureRepository;
use App\Repository\ProductRepository;
use App\Repository\ProjectRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TtkComponentRepository;
use App\Repository\TtkRepository;
use App\Repository\TypeProductRepository;
use App\Service\FileUploader;
use App\Utility\FirstProduct;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
                                         TypeProductRepository $typeProductRepository,
                                         IikoTtkComponentRepository $iikoTtkComponentRepository,
                                         IikoTtkRepository $iikoTtkRepository,
                                         IikoProductRepository $iikoProductRepository,
                                         Request $request)
    {


        $json = $request->query->get('product');
        $product_info = json_decode($json);

        $entityManager = $this->getDoctrine()->getManager();


        $product = $productRepository->findOneBy(['id'=>$product_info->id]);

        $category = $categoryRepository->findOneBy(['id'=>$product_info->selected_category]);

        $subcategory = $subcategoryRepository->findOneBy(['id'=>$product_info->selected_subcategory]);

        $type = $typeProductRepository->findOneBy(['id'=>$product_info->type]);

        $product->setCategory($category);
        $product->setSubcategory($subcategory);
        $product->setType($type);

        $ttk = $ttkRepository->findOneBy(['product'=>$product]);

        if (!$ttk){
            $ttk = new Ttk();
            $product->addTtk($ttk);

            $entityManager->persist($ttk);
        }

        $ttk->setNumber($product_info->ttk_num);
        $ttk->setComment($product_info->comment);
        $ttk->setTechnology($product_info->technology);
        $ttk->setName($product_info->name);

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

            $component = false;

            if (($component_item->iiko == true) or ($component_item->iiko == "true")){
                $iiko_component = $iikoTtkComponentRepository->findOneBy(['id'=>$component_id]);

                $iiko_product_id = $iiko_component->getIikoProductId();

                $component = $componentRepository->findOneBy(['iiko_id' => $iiko_product_id]);

            } else {
                $component = $componentRepository->findOneBy(['id' => $component_id]);
            }

            if (!$component){
                $component = new Component();

                if (isset($iiko_product_id)){
                    $component->setIikoId($iiko_product_id);
                    $component->setName($component_item->text);
                }
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
     * @Route("/ajax/{id}/product/getiikottk", name="get_ttk", methods={"GET"})
     */
    public function getiikoTtk(Project $project,
                           Request $request,
                           MeasureRepository $measureRepository,
                           ProductRepository $productRepository,
                           IikoProductRepository $iikoProductRepository,
                           IikoTtkRepository $iikoTtkRepository,
                           IikoTtkComponentRepository $ttkComponentRepository,
                           FirstProduct $firstProduct)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $iiko_product_query = json_decode($request->query->get('iiko_product'));
        $iiko_product_id = $iiko_product_query->id;
        $iiko_product = $iikoProductRepository->findOneBy(['id'=>$iiko_product_id]);

        $ttk = $iikoTtkRepository->findOneBy(['iiko_product_id'=>$iiko_product->getIikoId()]);

        $ttk_komponents = $ttk->getIikoTtkComponents();

        $arr_response = [];


        $arr_response = ['ttk_num'=>$iiko_product->getNum(),
            'technology' => $ttk->getApperance(),
            'comment' => $ttk->getDescription().' '. $ttk->getDescriptionSecond()
            ];

        foreach ($ttk_komponents as $component_item){

            $iiko_product_comp = $iikoProductRepository->findOneBy(['iiko_id'=>$component_item->getIikoProductId()]);


            $measure = $measureRepository->findOneBy(['iiko_id' => $iiko_product_comp->getMainUnit()]);

            if (!$measure){
                $measure_id = 3;
                $measure_name = 'кг';
            } else {
                $measure_id = $measure->getId();
                $measure_name = $measure->getName();
            }
            $arr_response['components'][] = array(
                'component_name' => $iiko_product_comp->getName(),
                'component_id' => $iiko_product_comp->getId(),
                'measure' => $measure_id,
                'measure_name' => $measure_name,
                'count' => $component_item->getNetto(),
                'iiko'=>true
            );
        }

        $json_respoonse = json_encode($arr_response);

        $response = JsonResponse::fromJsonString($json_respoonse);

        return $response;
    }

    /**
     * @Route("/ajax/{id}/product/{product_id}/addfooto", name="add_foto", methods={"POST"})
     */

    public function uploadFile(Project $project, $product_id,
                               Request $request,
                               \App\Utility\FileUploader $fileUploader,
                               ProductRepository $productRepository){

        $file = $request->files->get('file');


        $product = $productRepository->findOneBy(['id'=>$product_id]);
        $fileName = $fileUploader->upload($file);

        $entityManager = $this->getDoctrine()->getManager();
        $photo = new Photo();
        $photo->setImg($fileName);
        $photo->settype(5);
        $photo->setStatus(4);
        $photo->setProduct($product);

        $entityManager->persist($photo);
        $entityManager->flush();
        $response = JsonResponse::fromJsonString(json_encode(['img_name'=>$fileName]));
        return $response;

    }



    /**
     * @Route("/ajax/{id}/product/delete/", name="product_delet", methods={"DELETE"})
     */
    public function delete(Project $project,
                           Request $request,
                           ProductRepository $productRepository,
                           FirstProduct $firstProduct)
    {
        $json = $request->request->get('product');
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
