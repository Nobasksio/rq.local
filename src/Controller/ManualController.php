<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Project;
use App\Entity\Ttk;
use App\Repository\CategoryRepository;
use App\Repository\ComponentRepository;
use App\Repository\MeasureRepository;
use App\Repository\ProductRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TtkComponentRepository;
use App\Repository\TtkRepository;
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

class ManualController extends AbstractController
{
    /**
     * @Route("/project/{id}/manual", name="manual")
     */
    public function index(Project $project,
                          CategoryRepository $cr,
                          MeasureRepository $mr,
                          SubcategoryRepository $sr,
                          ProductRepository $productRepository,
                          TtkComponentRepository $ttkComponentRepository,
                          ComponentRepository $componentRepository)
    {

        $categories = $cr->findBy(['project' => $project, 'status' => true]);

        $measures = $mr->findAll();
        $components = $componentRepository->findAll();
        $subcategories = $sr->findAll();
        $categories_json = json_encode($categories);
        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();

        $products = $categories[0]->getProducts();

        $products = $productRepository->findBy(['category' => $categories[0], 'old_status' => 2]);
        $product = $products[0];

        $ttk = $product->getTtk();

        $product_components = $ttkComponentRepository->findBy(['Ttk' => $ttk[0]]);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $products_json = $serializer->serialize($products, 'json', ['attributes' => ['id', 'nameWork']]);
        $categories_json = $serializer->serialize($categories, 'json', ['attributes' => ['id', 'name']]);
        $measures_json = $serializer->serialize($measures, 'json', ['attributes' => ['id', 'name']]);
        $components_json = $serializer->serialize($components, 'json', ['attributes' => ['id', 'name']]);
        $subcategories_json = $serializer->serialize($subcategories, 'json', ['attributes' => ['id', 'name']]);
//        $product_components_json = $serializer->serialize($product_components, 'json',['attributes' => ['component_id', 'measure_id','count']]);

        //$measures_json = str_replace('name','text',$measures_json);
        $html = $this->render('manual/index.html.twig', [
            'first_product' => $product,
            'controller_name' => 'ManualController',
            'categories' => $categories,
            'selected_categoory' => $categories[0],
            'ttk' => $ttk[0],
            'products_json' => $products_json,
            'product_components' => $product_components,
            'subcategories_json' => $subcategories_json,
            'components_json' => $components_json,
            'categories_json' => $categories_json,
            'measures_json' => $measures_json,
            'project' => $project
        ]);

        return $html;
    }

    /**
     *
     * @Route ("/ajax/{id}/manual/product/{product_id}", name="manual_product")
     */
    public function ajaxManualProoduct(Project $project,
                                       $product_id,
                                       ProductRepository $productRepository,
                                       TtkRepository $ttkRepository,
                                       TtkComponentRepository $ttkComponentRepository)
    {


        $entityManager = $this->getDoctrine()->getManager();

        $product = $productRepository->findOneBy(['id' => $product_id]);
        $response_arr = array('id' => $product_id);
        $response_arr['selected_category'] = $product->getCategory()->getId();
        $subcategory = $product->getSubcategory();
        if ($subcategory) {
            $response_arr['selected_subcategory'] = $subcategory->getId();
        } else {
            $response_arr['selected_subcategory'] = "0";
        }
        $response_arr['name'] = $product->getNameWork();

        $ttk = $ttkRepository->findOneBy(['product' => $product]);

        if (!$ttk) {
            $ttk = new Ttk();

            $ttk->setProduct($product);
            $entityManager->persist($ttk);
            $entityManager->flush();
        }

        $response_arr['ttk_num'] = $ttk->getNumber();
        if ($ttk->getNumber() == null) {
            $response_arr['ttk_num'] = '';
        }

        $response_arr['comment'] = $ttk->getComment();
        if ($ttk->getComment() == null) {
            $response_arr['comment'] = '';
        }
        $response_arr['technology'] = $ttk->getTechnology();
        if ($ttk->getTechnology() == null) {
            $response_arr['technology'] = '';
        }

        $Components = $ttkComponentRepository->findBy(['Ttk' => $ttk]);


        $response_arr['components'] = array();
        foreach ($Components as $component_item) {
            $component = $component_item->getComponent();
            $measure = $component_item->getMeasure();
            $count = $component_item->getCount();

            $response_arr['components'][] = array(
                'component_name' => $component->getName(),
                'component_id' => $component->getId(),
                'measure' => $measure->getId(),
                'measure_name' => $measure->getName(),
                'count' => $count

            );


        }
        $json_respoonse = json_encode($response_arr);
        $json_respoonse = str_replace('null', '', $json_respoonse);
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
                                       CategoryRepository $categoryRepository)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $categoory = $categoryRepository->findOneBy(['id' => $category_id]);
        $response_arr = $firstProduct->getFirstProduct($categoory);

        $json_respoonse = json_encode($response_arr);
        $json_respoonse = str_replace('null', '', $json_respoonse);
        $response = JsonResponse::fromJsonString($json_respoonse);

        return $response;

    }



}
