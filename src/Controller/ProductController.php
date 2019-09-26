<?php

namespace App\Controller;

use App\Entity\Component;
use App\Entity\DescriptionMenu;
use App\Entity\NameMenu;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\Project;
use App\Entity\Ttk;
use App\Entity\TtkComponent;
use App\Repository\CategoryRepository;
use App\Repository\ComponentRepository;
use App\Repository\DescriptionMenuRepository;
use App\Repository\IikoProductRepository;
use App\Repository\IikoTtkComponentRepository;
use App\Repository\IikoTtkRepository;
use App\Repository\MeasureRepository;
use App\Repository\NameMenuRepository;
use App\Repository\PhotoRepository;
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

class ProductController extends BaseController
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
     * @Route("/project/{project_id}/product/{id}/edit", name="product")
     */
    public function edit(ProjectRepository $projectRepository,
                         $project_id,
                         CategoryRepository $categoryRepository,
                         FirstProduct $firstProduct,
                         Product $product)
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);

        $categories = $categoryRepository->findBy(['project' => $project, 'status' => true]);


        $array_category = $this->make_array($categories);

        $product_array = $firstProduct->getProductInfoArray($product);
        $app_state = json_encode([
            'product' => $product_array,
            'categories' => $array_category,
            'project' => ['id'=>$project->getId()]
        ]);
        return $this->render('product/edit.html.twig', [
            'app_state' => $app_state,
            'project' => $project
        ]);
    }

    /**
     * @Route("/ajax/{product}/product/update", name="update_product", methods={"POST"})
     */
    public function update_product(Product $product,
                                   Request $request,
                                   NameMenuRepository $NameMenuRepository,
                                   DescriptionMenuRepository $descriptionMenuRepository,
                                   CategoryRepository $categoryRepository,
                                   FirstProduct $firstProduct
    )
    {

        $data = $request->request->get('data');
        $data = json_decode(json_encode(json_decode($data)), true);
        $data_array = $data['product'];
        $category_array = $data['category'];
        $entityManager = $this->getDoctrine()->getManager();


        $user = $this->getUser();

        if ($product->getNameWork() != $data_array['name_work']) {
            $product->setNameWork($data_array['name_work']);
        }



        if ($product->getCategory()->getId() != $category_array['id']){
            $category = $categoryRepository->findOneBy(['id'=>$category_array['id']]);
            $product->setCategory($category);
        }


        if ($data_array['name_menu'] != null) {
            $lastNameMenu = $NameMenuRepository->findOneBy(['product' => $product, 'active' => true]);
            if ($lastNameMenu) {
                if ($lastNameMenu->getName() != $data_array['name_menu']) {

                    $lastNameMenu->setActive(false);
                    $entityManager->persist($lastNameMenu);

                    $futureNameMenu = new NameMenu();
                    $futureNameMenu->setUser($user);
                    $futureNameMenu->setName($data_array['name_menu']);
                    $futureNameMenu->setProduct($product);

                    $entityManager->persist($futureNameMenu);
                    $entityManager->flush();
                }
            } else {
                $futureNameMenu = new NameMenu();
                $futureNameMenu->setUser($user);
                $futureNameMenu->setName($data_array['name_menu']);
                $futureNameMenu->setProduct($product);

                $entityManager->persist($futureNameMenu);
                $entityManager->flush();
            }
        }

        if ($product->getCostPrice() != $data_array['cost_price']) {
            $product->setCostPrice($data_array['cost_price']);
        }

        if ($product->getOldPrice() != $data_array['old_price']) {
            $product->setOldPrice($data_array['old_price']);
            if ($product->getOldProduct()){
                $oldproduct = $product->getOldProduct();
                $oldproduct->setPrice($data_array['old_price']);
                $entityManager->persist($oldproduct);
                $entityManager->flush();
            }
        }

        if ($product->getPrice() != $data_array['price']) {
            $product->setPrice($data_array['price']);
        }

        if ($product->getWeight() != $data_array['ves']) {
            $product->setWeight($data_array['ves']);
        }

        if ($product->getConsist() != $data_array['consist']) {
            $product->setConsist($data_array['consist']);
        }


        if ($data_array['description_menu'] != null) {
            $lastDescriptionMenu = $descriptionMenuRepository->findOneBy(['Product' => $product, 'active' => true]);
            if ($lastDescriptionMenu) {
                if ($lastDescriptionMenu->getDescription() != $data_array['description_menu']) {
                    $lastDescriptionMenu->setActive(false);

                    $entityManager->persist($lastDescriptionMenu);

                }
            } else {
                $futureDescriptionMenu = new DescriptionMenu();
                $futureDescriptionMenu->setUser($user);
                $futureDescriptionMenu->setDescription($data_array['description_menu']);
                $futureDescriptionMenu->setProduct($product);

                $entityManager->persist($futureDescriptionMenu);
                $entityManager->flush();
            }
        }
        $entityManager->persist($product);
        $entityManager->flush();

        $response = JsonResponse::fromJsonString(1);
        return $response;

    }

    /**
     * @Route("/ajax/{project_id}/product/add", name="add_product_ajax")
     */
    public
    function newProductAjax(ProjectRepository $projectRepository, $project_id, Request $request, CategoryRepository $categoryRepository)
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $product = new Product();
        $entityManager = $this->getDoctrine()->getManager();

        $status = $request->query->get('status');
        $category_id = $request->query->get('category');
        $category = $categoryRepository->findOneBy(['id' => $category_id]);

        $product->setNameWork("Новое блюдо");
        $product->setProject($project);
        //product->setType($type);
        $product->setOldStatus(2);
        $product->setCategory($category);


        $entityManager->persist($product);

        $entityManager->flush();
        $response_arr = array('id' => $product->getId());
        $json_respoonse = json_encode($response_arr);
        $response = JsonResponse::fromJsonString($json_respoonse);
        return $response;
    }

    //надо переписать все обращения к add_product_ajax на add_product_ajax2

    /**
     * @Route("/ajax/{id}/product/add2", name="add_product_ajax2")
     */
    public
    function newProductAjax2(Project $project,
                             Request $request,
                             TypeProductRepository $typeProductRepository,
                             CategoryRepository $categoryRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->request->get('data'));
        $data = json_decode(json_encode($data->new_product), true);

        $product = new Product();


        if (isset($data['name']) and $data['name'] != null) {
            $product->setNameWork($data['name']);
        } else {
            $product->setNameWork('Новое блюдо');
        }
        $product->setProject($project);

        if (isset($data['type']) and $data['type'] != null) {
            $type = $typeProductRepository->findOneBy(['id' => $data['type']]);
            $product->setType($type);
        } else {
            $type = $typeProductRepository->findOneBy(['id' => 1]);
            $product->setType($type);
        }

        if (isset($data['status']) and $data['status'] != null) {
            $product->setOldStatus($data['status']);
        } else {
            $product->setOldStatus(2);
        }

        if (isset($data['consist']) and $data['consist'] != null) {
            $product->setConsist($data['consist']);
        }

        if (isset($data['weight']) and $data['weight'] != null) {
            $product->setWeight($data['weight']);
        }
        if (isset($data['cost_price']) and $data['cost_price'] != null) {
            $product->setCostPrice($data['cost_price']);
        }

        if (isset($data['old_price']) and $data['old_price'] != null) {
            $product->setOldPrice($data['old_price']);
        }

        $category = $categoryRepository->findOneBy(['id' => $data['category_id']]);
        if ($category) {
            $product->setCategory($category);
        }


        $entityManager->persist($product);

        $entityManager->flush();
        $response_arr = array('id' => $product->getId());
        $json_respoonse = json_encode($response_arr);
        $response = JsonResponse::fromJsonString($json_respoonse);
        return $response;
    }

    /**
     * @Route("/ajax/{id}/product/update_ttk", name="update_product_ajax", methods={"POST"})
     */
    public
    function updateProductTtkAjax(Project $project,
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


        $json = $request->request->get('data');
        $product_info = json_decode($json);

        $product_info = $product_info->product;

        $entityManager = $this->getDoctrine()->getManager();

        $category = $categoryRepository->findOneBy(['id' => $product_info->selected_category]);
        $subcategory = $subcategoryRepository->findOneBy(['id' => $product_info->selected_subcategory]);

        $product = $productRepository->findOneBy(['id' => $product_info->id]);

        if (!$product) {
            $product = new Product();
            $product->setNameWork($product_info->name);
            $product->setProject($project);
            //$product->setStatus($status);
            $product->setOldStatus(2);
            $product->setCategory($category);
            $entityManager->persist($product);
            $entityManager->flush();
        }

        $type = $typeProductRepository->findOneBy(['id' => $product_info->type]);

        $product->setCategory($category);
        if ($product->getNameWork() == 'Новое блюдо') {
            $product->setNameWork($product_info->name);
        }
        $product->setSubcategory($subcategory);
        $product->setType($type);


        $ttk = $ttkRepository->findOneBy(['product' => $product]);

        if (!$ttk) {
            $ttk = new Ttk();

            $entityManager->persist($ttk);
            $entityManager->flush();

            $ttk->setProduct($product);


        }

        if (isset($product_info->iiko_ttk->id)) {
            $iiko_product_id = $product_info->iiko_ttk->id;

            $iiko_product = $iikoProductRepository->findOneBy(['id' => $iiko_product_id]);

            if ($iiko_product) {
                $ttk->setIikoId($iiko_product->getIikoId());
            }
        }


        $ttk->setNumber($product_info->ttk_num);
        $ttk->setComment($product_info->comment);
        $ttk->setTechnology($product_info->technology);
        $ttk->setName($product_info->name);

        $ttkCompoonents = $ttk->getTtkComponents();
        foreach ($ttkCompoonents as $ttkCompoonent) {
//            $ttk->removeTtkComponent($ttkCompoonent);
            $entityManager->remove($ttkCompoonent);
        }
        $entityManager->persist($ttk);
        $entityManager->flush();


        $components = $product_info->components;

        foreach ($components as $component_item) {

            $component_id = $component_item->id;
            $measure_id = $component_item->measure;
            $count = $component_item->count;

            $component = false;

            if (isset($component_item->iiko)) {
                if (($component_item->iiko == true) or ($component_item->iiko == "true")) {
                    $iiko_component = $iikoProductRepository->findOneBy(['id' => $component_id]);

                    $iiko_product_id = $iiko_component->getIikoId();

                    $component = $componentRepository->findOneBy(['iiko_id' => $iiko_product_id]);

                } else {
                    $component = $componentRepository->findOneBy(['id' => $component_id]);
                }
            } else {
                $component = $componentRepository->findOneBy(['id' => $component_id]);
            }

            if (!$component) {
                $component = new Component();

                if (isset($iiko_product_id)) {
                    $component->setIikoId($iiko_product_id);
                    $component->setName($component_item->text);
                }
                $entityManager->persist($component);
            }

            $measure = $measureRepository->findOneBy(['id' => $measure_id]);

            $ttkComponent = $ttkComponentRepository->findOneBy(['Ttk' => $ttk, 'component' => $component, 'measure' => $measure]);

            if ($ttkComponent) {
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
    public
    function getiikoTtk(Project $project,
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
        $iiko_product = $iikoProductRepository->findOneBy(['id' => $iiko_product_id]);

        $ttk = $iikoTtkRepository->findBy(['iiko_product_id' => $iiko_product->getIikoId()],['date_from'=> 'Desc'],['limit'=>1]);

        $ttk_komponents = $ttk[0]->getIikoTtkComponents();

        $arr_response = [];


        $arr_response = ['ttk_num' => $iiko_product->getNum(),
            'technology' => $ttk[0]->getApperance(),
            'comment' => $ttk[0]->getDescription() . ' ' . $ttk[0]->getDescriptionSecond()
        ];

        foreach ($ttk_komponents as $component_item) {

            $iiko_product_comp = $iikoProductRepository->findOneBy(['iiko_id' => $component_item->getIikoProductId()]);


            $measure = $measureRepository->findOneBy(['iiko_id' => $iiko_product_comp->getMainUnit()]);

            if (!$measure) {
                $measure_id = 3;
                $measure_name = 'кг';
            } else {
                $measure_id = $measure->getId();
                $measure_name = $measure->getName();
            }
            $arr_response['components'][] = array(
                'component_name' => $iiko_product_comp->getName(),
                'component_id' => $iiko_product_comp->getId(),
                'id' => $iiko_product_comp->getId(),
                'name' => $iiko_product_comp->getName(),
                'text' => $iiko_product_comp->getName(),
                'measure' => $measure_id,
                'measure_name' => $measure_name,
                'count' => $component_item->getNetto(),
                'iiko' => true
            );
        }

        $json_respoonse = json_encode($arr_response);

        $response = JsonResponse::fromJsonString($json_respoonse);

        return $response;
    }

    /**
     * @Route("/ajax/product/{product_id}/addfooto/{status}/{type}", name="add_foto", methods={"POST"})
     */

    public
    function uploadFile($product_id,
                        $status, $type,
                        Request $request,
                        \App\Utility\FileUploader $fileUploader,
                        ProductRepository $productRepository)
    {

        $file = $request->files->get('file');


        $product = $productRepository->findOneBy(['id' => $product_id]);
        $fileName = $fileUploader->upload($file);

        $entityManager = $this->getDoctrine()->getManager();
        $photo = new Photo();
        $photo->setImg($fileName['img']);
        $photo->setPreview($fileName['preview']);
        $photo->settype($type);
        $photo->setStatus($status);
        $photo->setProduct($product);

        $entityManager->persist($photo);
        $entityManager->flush();

        $array_new_photo = ['preview' => $fileName['preview'],
            'img' => $fileName['img'],
            'id' => $photo->getId(),
            'isMain' => false,
            'type' => $type];

        $json_respoonse = json_encode($array_new_photo);
        $response = JsonResponse::fromJsonString($json_respoonse);
        return $response;

    }

    /**
     * @Route("/ajax/product/{product_id}/photo/{photo_id}", name="delete_foto",  methods={"DELETE"})
     */

    public function deletePhoto($product_id,
                        $photo_id,
                        Request $request,
                        ProductRepository $productRepository,
                        PhotoRepository $photoRepository)
    {

        $product = $productRepository->findOneBy(['id' => $product_id]);
        $photo = $photoRepository->findOneBy(['id' => $photo_id]);

        $photo->setStatus(0);


        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($photo);
        $entityManager->flush();


        $json_respoonse = json_encode(1);
        $response = JsonResponse::fromJsonString($json_respoonse);
        return $response;

    }


    /**
     * @Route("/ajax/{id}/product/delete/", name="product_delet", methods={"DELETE"})
     */
    public
    function delete(Project $project,
                    Request $request,
                    ProductRepository $productRepository,
                    FirstProduct $firstProduct)
    {
        $json = $request->query->get('product');
        $product_info = json_decode($json);

        $entityManager = $this->getDoctrine()->getManager();

        $product = $productRepository->findOneBy(['id' => $product_info->id]);

        $product->setOldStatus(0);


        $entityManager->persist($product);

        $entityManager->flush();

        $category = $product->getCategory();

       // $response_arr = $firstProduct->getFirstProduct($category);
        $json_respoonse = json_encode(['status'=>0]);
        $response = JsonResponse::fromJsonString($json_respoonse);

        return $response;
    }

}
