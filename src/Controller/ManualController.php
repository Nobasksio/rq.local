<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Project;
use App\Entity\Ttk;
use App\Entity\TypeProduct;
use App\Repository\CategoryRepository;
use App\Repository\ComponentRepository;
use App\Repository\IikoCategoryRepository;
use App\Repository\IikoProductRepository;
use App\Repository\MeasureRepository;
use App\Repository\PhotoRepository;
use App\Repository\ProductRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TtkComponentRepository;
use App\Repository\TtkRepository;
use App\Repository\TypeProductRepository;
use App\Utility\FirstProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

class ManualController extends BaseController
{
    private $iikoCategoryRepository;
    private $arr_iiko_category;
    /**
     * @Route("/project/{id}/manual", name="manual")
     */
    public function index(Project $project,
                          CategoryRepository $cr,
                          MeasureRepository $mr,
                          SubcategoryRepository $sr,
                          FirstProduct $firstProduct,
                          ProductRepository $productRepository,
                          TtkComponentRepository $ttkComponentRepository,
                          IikoProductRepository $iikoProductRepository,
                          IikoCategoryRepository $iikoCategoryRepository,
                          TypeProductRepository $typeProductRepository,
                          ComponentRepository $componentRepository)
    {

        $categories = $cr->findBy(['project' => $project, 'status' => true]);
        $measures = $mr->findAll();
        $components = $componentRepository->findAll();
        $types_product = $typeProductRepository->findBy(['active' => true]);
        $subcategories = $sr->findAll();
        $products = $productRepository->findBy(['category' => $categories[0], 'old_status' => 2]);

        $product = $products[0];
        $ttk = $product->getTtk();
        $product_components = $ttkComponentRepository->findBy(['Ttk' => $ttk[0]]);

        if ($ttk[0]->getName() == null){
            $names_menu = $product->getNamesMenu();
            if (count($names_menu)>0){
                foreach ($names_menu as $nameMenu){
                    if ($nameMenu->getActive()){
                        $ttk[0]->setName($nameMenu->getName());
                    }
                }
            } else {
                $ttk[0]->setName($product->getNameWork());
            }
        }



        foreach ($project->getIikoDepartment() as $iiko_department){
            $project_departments[] = array('id'=> $iiko_department->getId(),
                'name'=>$iiko_department->getName());
            if (count($iiko_department->getIikoCategories())>0) {
                foreach( $iiko_department->getIikoCategories() as $category){
                    $array_categories[] = $category;
                }
            }
        }

        foreach ($project->getIikoCategories() as $category){
            $array_categories[] = $category;
        }

        $array_iiko_test = [];
        foreach ($array_categories as $category_item){
            $array_iiko_test[] = [
                'id'=>$category_item->getId(),
                'name'=>$category_item->getName()
            ];
        }

        $this->iikoCategoryRepository = $iikoCategoryRepository;

        $this->CreateTree($array_categories);


        $check_array = [];

        foreach ($this->arr_iiko_category as $key => $iiko_category){
            if (!in_array($iiko_category['id'],$check_array)){
                $check_array[] = $iiko_category['id'];
            } else {
                unset($this->arr_iiko_category[$key]);
            }
        }

        $iiko_products = $iikoProductRepository->findBy(['parent'=>$check_array]);

        $iiko_products_array = $this->make_array($iiko_products);


        $products_array = $this->make_array($products);
        $categories_array = $this->make_array($categories);
        $measures_array = $this->make_array($measures);
        $components_array = $this->make_array($components);
        $subcategories_array = $this->make_array($subcategories);
        $types_product_array = $this->make_array($types_product);


        $app_state = json_encode([
            'first_product' =>$firstProduct->getFirstProduct($categories[0]),
            'types_product' => $types_product_array,
            'categories' => $categories_array,
            'selected_categoory' => $categories[0]->getId(),
            'iiko_category' =>$array_iiko_test,
            'products' => $products_array,
            'subcategories' => $subcategories_array,
            'components' => $components_array,
            'measures' => $measures_array,
            'iiko_products' =>$iiko_products_array,
            'project_id' => $project->getId()

        ]);


        $html = $this->render('manual/index.html.twig', [
            'app_state' => addslashes($app_state),
            'project' =>$project
        ]);

        return $html;
    }

    /**
     * @Route("/project/{id}/manual_down", name="manual_down")
     */

    public function manual_down(Project $project,
                          CategoryRepository $cr,
                          MeasureRepository $mr,
                          SubcategoryRepository $sr,
                          FirstProduct $firstProduct,
                          ProductRepository $productRepository,
                          TtkComponentRepository $ttkComponentRepository,
                          IikoProductRepository $iikoProductRepository,
                          IikoCategoryRepository $iikoCategoryRepository,
                          TypeProductRepository $typeProductRepository,
                          ComponentRepository $componentRepository)
    {

        $categories = $cr->findBy(['project' => $project, 'status' => true]);
        $categories_array = $this->make_array($categories);

        $products = $productRepository->findBy(['category' => $categories, 'old_status' => 2]);

        foreach ($products as $product) {
            $products_array[] = $firstProduct->getProductInfoArray($product);
        }

        $app_state = json_encode([
            'selected_category' => $categories[0]->getId(),
            'categories' =>$categories_array,
            'products' => $products_array

    ]);
        $html = $this->render('manual/index_an.html.twig', [
            'project' =>$project,
            'app_state' => $app_state,
        ]);

        return $html;
    }

    /**
     *
     * @Route ("/ajax/{id}/manual/product/{product_id}", name="manual_product")
     */
    public function ajaxManualProoduct(Project $project,
                                       ProductRepository $productRepository,
                                       ComponentRepository $componentRepository,
                                       FirstProduct $firstProduct,
                                       $product_id)
    {

        $product = $productRepository->findOneBy(['id' => $product_id]);
        $components = $componentRepository->findAll();
        $components_array = $this->make_array($components);
        $response_arr = $firstProduct->getProductInfoArray($product);
        $json_respoonse = json_encode(['product' => $response_arr,'components'=>$components_array]);
        $response = JsonResponse::fromJsonString($json_respoonse);

        return $response;
    }

    /**
     *
     * @Route ("/ajax/{id}/manual/category/{category_id}", name="manual_category")
     */
    public function ajaxManualCategory(Project $project,
                                       $category_id,
                                       FirstProduct $firstProduct,
                                       ComponentRepository $componentRepository,
                                       CategoryRepository $categoryRepository)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $components = $componentRepository->findAll();
        $components_array = $this->make_array($components);


        $categoory = $categoryRepository->findOneBy(['id' => $category_id]);
        $response_arr = $firstProduct->getFirstProduct($categoory);

        $json_respoonse = json_encode(['product'=>$response_arr,'components'=>$components_array]);

        $response = JsonResponse::fromJsonString($json_respoonse);

        return $response;

    }



    function CreateTree($array_categories)
    {
        //asort($array);

        $array_out = [];
        foreach($array_categories as $iikoCategory){
            $this->arr_iiko_category[] = ['id'=>$iikoCategory->getIikoId(),
                    'name' => $iikoCategory->getName()];

            $categories_more = $this->iikoCategoryRepository->findBy(['parent' => $iikoCategory->getIikoId()]);
            if (count($categories_more)>0) {
                $this->CreateTree($categories_more);
            }
        }
        return $array_out;
    }


}
