<?php

namespace App\Controller;

use App\Entity\Analitics;

use App\Entity\Category;
use App\Entity\IikoTtk;
use App\Entity\IikoTtkComponent;
use App\Entity\OldCategory;
use App\Entity\OldProduct;
use App\Entity\Project;
use App\Entity\TtkComponent;
use App\Factory\ProductFactory;
use App\Form\AnaliticsType;
use App\Form\Analitics2Type;
use App\Repository\AnaliticsRepository;
use App\Repository\DepartmentRepository;
use App\Repository\IikoProductRepository;
use App\Repository\IikoTtkComponentRepository;
use App\Repository\IikoTtkRepository;
use App\Repository\MeasureRepository;
use App\Repository\OldCategoryRepository;
use App\Repository\OldProductRepository;
use App\Repository\ProductRepository;
use App\Repository\ProjectRepository;
use App\Utility\AnaliticsUtil;
use App\Utility\FirstProduct;
use App\Utility\iikoDownloadUtil;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AnaliticsController extends BaseController
{

    /**
     * @Route("/project/{project_id}/analitics", name="analitics", methods={"GET"})
     */
    public function index($project_id, ProjectRepository $projectRepository, AnaliticsRepository $analiticsRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        return $this->render('analitics/index.html.twig', [
            'analitics' => $analiticsRepository->findBy(['status' => true, 'project' => $project]),
            'project' => $project
        ]);
    }

    /**
     * @Route("/project/{id}/analitics/new", name="analitics_new", methods={"GET","POST"})
     */
    public function new(Project $project,
                        ProjectRepository $projectRepository,
                        Request $request,
                        DepartmentRepository $drepository,
                        iikoDownloadUtil $downloadUtil): Response
    {
        $analitic = new Analitics();
        $form = $this->createForm(AnaliticsType::class, $analitic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('upload_file'),
                    $fileName
                );
            } catch (FileException $e) {

            }


            $analitic->setFile($fileName);

            $date_start = \DateTime::createFromFormat('d.m.Y', $analitic->getDateStartStr());
            $analitic->setDateStart($date_start);

            $date_finish = \DateTime::createFromFormat('d.m.Y', $analitic->getDateFinishStr());
            $analitic->setDateFinish($date_finish);

            $analitic->setProject($project);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($analitic);
            $entityManager->flush();

            $file_content = file_get_contents(__DIR__ . '/../../public/uploads/file/' . $fileName);

            $this->saveProducts($file_content, $analitic);

            return $this->redirectToRoute('analitics', ['project_id' => $project->getId()]);
        }


        $departments = $drepository->findBy(['type' => 'DEPARTMENT']);

        foreach ($departments as $department) {
            $departments_array[] = array('id' => $department->getId(),
                'name' => $department->getName());
        }
        $project_departments = [];
        foreach ($project->getIikoDepartment() as $iiko_department) {
            $project_departments[] = array('id' => $iiko_department->getId(),
                'name' => $iiko_department->getName());
        }

        $app_state = json_encode([
            'departments' => $departments_array,
            'project_departments' => $project_departments,
            'project_id' => $project->getId(),
            'date_start' => null,
            'date_finish' => null,
            'categories' => []
        ]);
        return $this->render('analitics/new.html.twig', [
            'analitic' => $analitic,
            'form' => $form->createView(),
            'project' => $project,
            'app_state' => $app_state
        ]);
    }

    /**
     * @Route("/ajax/project/{id}/analitics/download", name="analitics_download_iiko", methods={"POST"})
     */
    public function GetIikoSale(Project $project,
                                iikoDownloadUtil $downloadUtil,
                                Request $request,
                                DepartmentRepository $departmentRepository,
                                OldCategoryRepository $oldCategoryRepository)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $setting = json_decode($request->request->get('analitic_setting'));

        $name = $setting->name;

        $date_start = new \DateTime($setting->date_start);
        $date_finish = new \DateTime($setting->date_finish);

        $selected_departments = $setting->selected_departments;


        $arr_departments_iiko_id = [];
        foreach ($selected_departments as $department_item) {

            $department = $departmentRepository->findOneBy(['id' => $department_item->id]);
            $arr_departments_iiko_id[] = $department->getCode();
        }

        $sales = $downloadUtil->getSales($arr_departments_iiko_id, $date_start, $date_finish);
        $data = json_decode(json_encode($sales->data), True);

        $analitic = new Analitics();

        $analitic->setName($name);

        $analitic->setType(2);

        $analitic->setProject($project);

        $analitic->setDateStart($date_start);
        $analitic->setDateFinish($date_finish);

        $entityManager->persist($analitic);
        $entityManager->flush();

        foreach ($data as $product_item) {


            $product_item['DishGroup'];
            $product_item['DishGroup.Num'];

            $old_category = $oldCategoryRepository->findOneBy(['iiko_code' => $product_item['DishGroup.Num'], 'analitics' => $analitic]);

            if (!$old_category) {
                $old_category = new OldCategory();
                $old_category->setName($product_item['DishGroup']);
                $old_category->setAnalitics($analitic);
                $old_category->setType(1);
                $old_category->setIikoCode($product_item['DishGroup.Num']);

                $entityManager->persist($old_category);
                $entityManager->flush();
            }

            $old_product = new OldProduct();

            $old_product->setCategory($old_category);
            $old_product->setPrice($product_item['DishDiscountSumInt.averagePrice']);
            $old_product->setcostPrice($product_item['ProductCostBase.OneItem']);
            $old_product->setSale($product_item['DishAmountInt']);
            $old_product->setName($product_item['DishName']);
            $old_product->setIikoCode($product_item['DishCode']);

            $entityManager->persist($old_product);

        }
        $entityManager->flush();

        $app_state = json_encode([
            'analitic_id' => $analitic->getId()
        ]);

        $response = JsonResponse::fromJsonString($app_state);

        return $response;

    }


    /**
     * @Route("/project/{project_id}/analitics/{id}", name="analitics_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show($project_id,
                         ProjectRepository $projectRepository,
                         Analitics $analitic,
                         FirstProduct $firstProduct,
                         OldCategoryRepository $oldCategoryRepository,
                         IikoProductRepository $iikoProductRepository,
                         IikoTtkRepository $iikoTtkRepository,
                         MeasureRepository $measureRepository,
                         OldProductRepository $oldProductRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);

        $categories = $oldCategoryRepository->findBy(['analitics' => $analitic, 'status' => true]);
        //$categoryes = $analitic->getOldCategories();


        foreach ($categories as $key => $category_item) {
            if (count($category_item->getOldProducts()) == 0) {
                unset($categories[$key]);
            }


        }

        $active_category_id = $oldCategoryRepository->findOneBy(['type' => 1, 'status' => true])->getId();

        $old_products = $oldProductRepository->findBy(['category' => $active_category_id]);

        $val_arr = AnaliticsUtil::setAbc($old_products);

        AnaliticsUtil::countPriceCat($old_products, $val_arr['mean_price']);
        AnaliticsUtil::setAbcFirstOtch($old_products, $val_arr);
        AnaliticsUtil::setAbcSecond($old_products, $val_arr);
        AnaliticsUtil::countSummary($categories);
        usort($old_products, 'App\Utility\mySortSale');

        $categories_array = $this->make_array($categories);

        $products_array = [];
        foreach ($old_products as $old_product_item) {
            $products_array[] = $old_product_item->getArrayParam();
        }
        $entityManager = $this->getDoctrine()->getManager();

        //$myau = $this->countNetto($iikoTtkRepository, $entityManager);
        //$this->countNettoall($iikoTtkRepository,$entityManager);
        $components_arr = $components_arr = $this->getMatrix($analitic, $iikoProductRepository, $iikoTtkRepository, $measureRepository, $entityManager);

        $app_state = json_encode([
            'products' => $products_array,
            'project_id' => $project->getId(),
            'categories' => $categories_array,
            'analitic_id' => $analitic->getId(),
            'components' => $components_arr
        ]);

        return $this->render('analitics/show.html.twig', [
            'analitic' => $analitic,
            'categoryes' => $categories,
            'project' => $project,
            'old_products' => $old_products,
            'val_array' => $val_arr,
            'old_category_id' => false,
            'app_state' => $app_state
        ]);
    }

    /**
     * @Route("/ajax/{project_id}/analitics/old_product/new_menu", name="move_to_menu",requirements={"project_id"="\d+"}, methods={"POST"})
     */

    public function updateStateNewMenu($project_id, Request $request,
                                       OldProductRepository $oldProductRepository,
                                       ProductFactory $productFactory,
                                       ProductRepository $productRepository){


        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->request->get('data'));
        $data = json_decode(json_encode($data), true);

        $old_product = $oldProductRepository->findOneBy(['id'=>$data['id']]);

        if ($old_product->getProduct()){
            $product = $old_product->getProduct();
        } else {
            $product = $productFactory->createBasedOldProduct($old_product);
        }

        if ($data['state']) {
            $product->setOldStatus(2);
        } else {
            $product->setOldStatus(0);
        }

        $old_product->setProduct($product);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->persist($old_product);
        $entityManager->flush();


        $response = JsonResponse::fromJsonString(1);

        return $response;

    }

    /**
     * @Route("/project/{project_id}/analitics/{id}/{old_category_id}", name="analitics_show_category",requirements={"id"="\d+", "old_category_id"="\d+"}, methods={"GET"})
     */
    public function showCategory($project_id, $old_category_id, ProjectRepository $projectRepository, Analitics $analitic, OldProductRepository $oldProductRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $categoryes = $analitic->getOldCategories();
        $active_category_id = $old_category_id;

        $old_products = $oldProductRepository->findBy(['category' => $active_category_id]);

        $val_arr = AnaliticsUtil::setAbc($old_products);

        AnaliticsUtil::countPriceCat($old_products, $val_arr['mean_price']);
        AnaliticsUtil::setAbcFirstOtch($old_products, $val_arr);
        AnaliticsUtil::setAbcSecond($old_products, $val_arr);


        usort($old_products, 'App\Utility\mySortSale');
        return $this->render('analitics/show.html.twig', [
            'analitic' => $analitic,
            'categoryes' => $categoryes,
            'project' => $project,
            'old_products' => $old_products,
            'val_array' => $val_arr,
            'old_category_id' => $old_category_id
        ]);
    }

    public function countNettoall(IikoTtkRepository $iikoTtkRepository, $entityManager)
    {

        $ttks = $iikoTtkRepository->findBy(['netto_ttk'=>null]);

        $i = 0;
        set_time_limit(0);

        foreach ($ttks as $ttk) {

            $netto = 0;
            foreach ($ttk->getIikoTtkComponents() as $ttkComponent) {
                $netto += $ttkComponent->getNetto();
            }
            $ttk->setNettoTtk($netto);

            $entityManager->persist($ttk);
            $i++;

            if ($i>500){
                $i = 0;
                $entityManager->flush();
            }
        }

        $entityManager->flush();

        return $netto;
    }


    public function countNetto(IikoTtk $ttk, IikoTtkRepository $iikoTtkRepository, $entityManager)
    {

        set_time_limit(0);


        $netto = 0;
        foreach ($ttk->getIikoTtkComponents() as $ttkComponent) {
            $netto += $ttkComponent->getNetto();
        }
        $ttk->setNettoTtk($netto);

        $entityManager->persist($ttk);

        $entityManager->flush();

        return $netto;
    }

    public function getMatrix(Analitics $analitics,
                              IikoProductRepository $iikoProductRepository,
                              IikoTtkRepository $iikoTtkRepository, MeasureRepository $measureRepository,
                              $entityManager
    )
    {
        $old_categories = $analitics->getOldCategories();

        $all_count = 0;
        $count_report = 0;
        $count_recurs = 0;
        $copmponents_arr = [];
        $copmponents_count_product_arr = [];

        $check_arr = [];

        set_time_limit(100);

        $count_triuble = 0;
        foreach ($old_categories as $oldCategory) {

//            if ($oldCategory->getId() != 649){
//                continue;
//            }

            $old_products = $oldCategory->getOldProducts();

            $all_count += count($old_products);
            $count_report += count($old_products);
            foreach ($old_products as $old_product) {

                $iiko_code = $old_product->getIikoCode();
                if ($old_product->getIikoCode() != null) {
                    $iiko_product = $iikoProductRepository->findOneBy(['num' => $iiko_code]);
                    if ($iiko_product) {
                        $iiko_id = $iiko_product->getIikoId();
                        $ttks = $iikoTtkRepository->findBy(['iiko_product_id' => $iiko_id]);

                        if (count($ttks) > 1) {
                            $count_triuble++;
                        }
                        if ($ttks) {

                            $return_arr = $this->recursSerach($copmponents_arr,
                                $copmponents_count_product_arr,
                                $ttks[0],
                                $iikoTtkRepository,
                                $old_product,
                                $count_recurs
                                );
                            $copmponents_arr = $return_arr[0];
                            $copmponents_count_product_arr = $return_arr[1];
                            $count_recurs += $return_arr[2];

                        } else {
                            $count_report--;
                        }
                    } else {
                        $count_report--;
                    }

                } else {
                    $count_report--;
                }

            }
        }

        $out_array = [];
        $meashur_trubl = [];
        foreach ($copmponents_arr as $iiko_id => $weight) {
            $iiko_product = $iikoProductRepository->findOneBy(['iiko_id' => $iiko_id]);

            if ($iiko_product) {
                $main_unit = $iiko_product->getMainUnit();

                $measure = $measureRepository->findOneBy(['iiko_id' => $main_unit]);

                if ($measure) {
                    $m_name = $measure->getName();
                } else {
                    $meashur_trubl[] = ['id' => $main_unit, 'name' => $iiko_product->getName()];
                    $m_name = 'не понятно';
                }
                $uniq = array_unique($copmponents_count_product_arr[$iiko_id]);
                $out_array[] = ['name' => $iiko_product->getName(),
                    'weight' => round($weight,2),
                    'unit' => $m_name,
                    'count' => count($uniq)
                ];
            }
        }


        return $out_array;


    }

    public function recursSerach($copmponents_arr,
                                 $copmponents_count_product_arr,
                                 IikoTtk $ttk,
                                 IikoTtkRepository $iikoTtkRepository,
                                 OldProduct $old_product,
                                 $count_recurs,
                                 $pre_netto_component = 0, $pre_coof = 1){

        $count_recurs++;
        $ttk_components = $ttk->getIikoTtkComponents();

        $ttk_netto = $ttk->getNettoTtk();



        if ($pre_netto_component == 0){
            $coof = 1;
        } else {
            if ($ttk_netto !=0) {
                $coof = (float)$pre_netto_component * $pre_coof / (float)$ttk_netto;
            } else {
                $coof = (float)$pre_netto_component * $pre_coof / (float)$pre_netto_component;
            }
        }

        foreach ($ttk_components as $ttkComponent){
            $iiko_product_id = $ttkComponent->getIikoProductId();
            $netto_component = $ttkComponent->getNetto();
            $netto = $coof * $netto_component * $old_product->getSale();

            if ($iiko_product_id == '717c486d-5642-413b-a28b-5dbae485d352'){
                $chec = 1;
            }

            $next_level_ttk = $iikoTtkRepository->findOneBy(['iiko_product_id' => $iiko_product_id]);

            if ($next_level_ttk){

                $return_arr = $this->recursSerach($copmponents_arr,
                    $copmponents_count_product_arr,
                    $next_level_ttk,
                    $iikoTtkRepository,
                    $old_product,
                    $count_recurs,
                    $netto_component,$coof);

                $copmponents_arr = $return_arr[0];
                $copmponents_count_product_arr = $return_arr[1];
                $count_recurs += $return_arr[2];
            } else {


                if (isset($copmponents_arr[$iiko_product_id])) {
                    $copmponents_arr[$iiko_product_id] += $netto;
                    $copmponents_count_product_arr[$iiko_product_id][] = $old_product->getId();

                } else {
                    $copmponents_arr[$iiko_product_id] = $netto;
                    $copmponents_count_product_arr[$iiko_product_id] = [$old_product->getId()];
                }
            }
        }

        return [
            $copmponents_arr,
            $copmponents_count_product_arr,
            $count_recurs
        ];


    }

    /**
     * @Route("/ajax/project/{project_id}/analitics/{old_category_id}", name="analitics_ajax", methods={"GET"})
     */
    public
    function showAjax($project_id, $old_category_id, ProjectRepository $projectRepository, OldProductRepository $oldProductRepository): Response
    {

        $products_array = $this->getOldProduct($old_category_id, $oldProductRepository);
        $app_state = json_encode([
            'products' => $products_array
        ]);

        $response = JsonResponse::fromJsonString($app_state);

        return $response;


    }

    public
    function getOldProduct($active_category_id, OldProductRepository $oldProductRepository)
    {

        $old_products = $oldProductRepository->findBy(['category' => $active_category_id]);

        $val_arr = AnaliticsUtil::setAbc($old_products);

        AnaliticsUtil::countPriceCat($old_products, $val_arr['mean_price']);
        AnaliticsUtil::setAbcFirstOtch($old_products, $val_arr);
        AnaliticsUtil::setAbcSecond($old_products, $val_arr);
        usort($old_products, 'App\Utility\mySortSale');

        foreach ($old_products as $old_product_item) {
            $products_array[] = $old_product_item->getArrayParam();
        }

        return $products_array;
    }

    /**
     * @Route("project/{project_id}/analitics/svodka/{analitics_id}", name="analitics_svodka", requirements={"project_id"="\d+", "analitics_id"="\d+"}, methods={"GET"})
     */
    public
    function svodka($project_id,
                    $analitics_id,
                    ProjectRepository $projectRepository,
                    AnaliticsRepository $analiticsRepository,
                    OldProductRepository $oldProductRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $analitic = $analiticsRepository->findOneBy(['id' => $analitics_id]);

        $categoryes = $analitic->getOldCategories();

        $old_products = [];
        foreach ($categoryes as $category_item) {

            AnaliticsUtil::countSummary($category_item->getOldProducts());
            $old_products = array_merge($old_products, $category_item->getOldProducts());
        }

        $old_products_array = [];

        foreach ($old_products as $old_product_item) {
            $old_products_array[$old_product_item->getId()] = $old_product_item->getVir();
        }
        arsort($old_products_array);

        $output = array_slice($old_products_array, 0, 10);

        $old_product_array_response = [];
        foreach ($output as $product_id => $vir) {
            $array_product = [];
            $old_product = $oldProductRepository->findOneBy(['id' => $product_id]);

            $array_product = $old_product->getArrayParam();
            $array_product['val_vir'] = $vir;

            $old_product_array_response[] = $array_product;
        }

        $app_state = json_encode([
            'products_top' => $old_product_array_response
        ]);

        $response = JsonResponse::fromJsonString($app_state);

        return $response;


//        return $this->render('analitics/show_svodka.html.twig', [
//            'categoryes' => $categoryes,
//            'project' => $project,
//            'analitic' => $analitic,
//            'old_category_id' => false
//        ]);

    }

    /**
     * @Route("ajax/svodka/{id}", name="analitics_svodka_ajax", methods={"GET"})
     */
    public
    function svodkaAjax(ProjectRepository $projectRepository,
                        Analitics $analitic,
                        OldProductRepository $oldProductRepository,
                        OldCategoryRepository $oldCategoryRepository): Response
    {


        $categoryes = $analitic->getOldCategories();


        $old_products_array = [];
        foreach ($categoryes as $category_item) {

            foreach ($category_item->getOldProducts() as $oldProduct) {
                $old_products_array[$oldProduct->getId()] = $oldProduct->getVir();
            }
        }


        arsort($old_products_array);

        $i = 0;

        $old_product_array_response = [];
        foreach ($old_products_array as $product_id => $vir) {
            $array_product = [];
            $old_product = $oldProductRepository->findOneBy(['id' => $product_id]);

            $array_product = $old_product->getArrayParam();
            $array_product['val_vir'] = $vir;

            $old_product_array_response[] = $array_product;

            $i++;

            if ($i > 9) {
                break;
            }

        }

        $app_state = json_encode([
            'products_top' => $old_product_array_response
        ]);

        $response = JsonResponse::fromJsonString($app_state);

        return $response;

    }

    /**
     * @Route("project/{project_id}/analitics_edit/{id}", name="analitics_edit", methods={"GET","POST"})
     */
    public
    function edit($project_id, ProjectRepository $projectRepository, Request $request, Analitics $analitic): Response
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        $form = $this->createForm(Analitics2Type::class, $analitic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('analitics', [
                'id' => $analitic->getId(),
                'project_id' => $project_id
            ]);
        }

        $old_categories = $analitic->getOldCategories();

        $arr_categories = [];

        foreach ($old_categories as $old_category) {

            $arr_categories[] = $old_category->getArrayParam();
        }

        $app_state = json_encode([
            'departments' => [],
            'project_departments' => [],
            'name' => $analitic->getName(),
            'project_id' => $project->getId(),
            'date_start' => $analitic->getDateStart()->format('Y-m-d'),
            'date_finish' => $analitic->getDateFinish()->format('Y-m-d'),
            'categories' => $arr_categories
        ]);

        return $this->render('analitics/edit.html.twig', [
            'analitic' => $analitic,
            'form' => $form->createView(),
            'project' => $project,
            'app_state' => $app_state
        ]);
    }

    /**
     * @Route("ajax/{project_id}/analitics/category/{id}/update_status", name="update_category_status", methods={"POST"})
     */

    public
    function update_status_category($project_id,
                                    OldCategory $oldCategory,
                                    ProjectRepository $projectRepository,
                                    Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($oldCategory->getStatus()) {
            $status = false;
            $string_status = 0;
        } else {
            $status = true;
            $string_status = 1;
        }

        $oldCategory->setStatus($status);
        $entityManager->persist($oldCategory);
        $entityManager->flush();

        return new Response($string_status);

    }

    /**
     * @Route("ajax/{project_id}/analitics/category/{id}/update_field", name="update_category_ield", methods={"POST"})
     */

    public
    function update_field_category($project_id,
                                   OldCategory $oldCategory,
                                   ProjectRepository $projectRepository,
                                   OldProductRepository $oldProductRepository,
                                   OldCategoryRepository $oldCategoryRepository,
                                   Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->request->get('data'));
        $data = json_decode(json_encode($data), true);

        foreach ($data as $key => $value) {
            $name_method = 'set' . ucfirst($key);


            if ($key == 'combine' and $value != null) {
                $combine_old_category = $oldCategoryRepository->findOneBy(['id' => $value]);

                $base_category_id = $oldCategory->getId();
                $old_products = $oldCategory->getOldProducts();
                foreach ($old_products as $old_product_item) {
                    $old_product_item->setBaseCategory($base_category_id);
                    $old_product_item->setCategory($combine_old_category);
                    $entityManager->persist($old_product_item);
                }
            }
            $oldCategory->$name_method($value);

        }

        $entityManager->persist($oldCategory);
        $entityManager->flush();

        return new Response(1);

    }

    /**
     * @Route("ajax/{project_id}/analitics/hide/{id}", name="analitics_hide")
     */
    public
    function hide($project_id, ProjectRepository $projectRepository, Request $request, Analitics $analitic): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($analitic->getStatus()) {
            $status = false;
            $string_status = 0;
        } else {
            $status = true;
            $string_status = 1;
        }

        $analitic->setStatus($status);
        $entityManager->persist($analitic);
        $entityManager->flush();

        return new Response($string_status);
    }

    /**
     * @Route("project/{project_id}/analitics/{id}", name="analitics_delete", methods={"DELETE"})
     */
    public
    function delete($project_id, ProjectRepository $projectRepository, Request $request, Analitics $analitic): Response
    {
        if ($this->isCsrfTokenValid('delete' . $analitic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($analitic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('analitics', ['project_id' => $project_id]);
    }

    private
    function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    //todo вынести это в отдельный класс
    public
    function saveProducts($file_contents,
                          Analitics $analitics)
    {
        $f_array_str = explode("\r", $file_contents);

        //delete last string if it empty
        $last_key = count($f_array_str) - 1;
        if ($f_array_str[$last_key] == '') {
            unset($f_array_str[$last_key]);
        }

        //get one product from file
        foreach ($f_array_str as $key => $value) {

            if ($key == 0) continue;

            $array_value[$key] = explode(";", $f_array_str[$key]);
            $group = $array_value[$key][0];
            $array_for_group[$group][] = $array_value[$key];

        }
        $doctrin = $this->getDoctrine();
        //перебираем весь массив чтобы сохранить
        foreach ($array_for_group as $cat_name => $cat_arr) {

            $old_category = new OldCategory();
            $old_category->setName($cat_name);
            $old_category->setAnalitics($analitics);

            $entityManager = $doctrin->getManager();
            $entityManager->persist($old_category);


            foreach ($cat_arr as $key_tov => $tov_arr) {

                $name_tov = $tov_arr[1];
                $sale = str_replace(",", '.', $tov_arr[2]);
                $sale = (float)preg_replace("/[^x\d|*\.]/", "", $sale);
                $price = str_replace(",", '.', $tov_arr[3]);
                $price = (float)preg_replace("/[^x\d|*\.]/", "", $price);
                $cost_price = str_replace(",", '.', $tov_arr[4]);
                $cost_price = (float)preg_replace("/[^x\d|*\.]/", "", $cost_price);


                $old_product = new OldProduct();

                $old_product->setName($name_tov);
                $old_product->setCategory($old_category);
                $old_product->setPrice($price);
                $old_product->setcostPrice($cost_price);
                $old_product->setSale($sale);

                $entityManager->persist($old_product);

            }

        }

        $entityManager->flush();
    }

}
