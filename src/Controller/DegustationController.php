<?php

namespace App\Controller;

use App\Entity\Degustation;
use App\Entity\DegustationScore;
use App\Entity\Product;
use App\Entity\Project;
use App\Repository\CategoryRepository;
use App\Repository\DegustationRepository;
use App\Repository\DegustationScoreRepository;
use App\Repository\ProductRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DegustationController extends BaseController
{
    /**
     * @Route("/project/{project_id}/degustation", name="degustations")
     */
    public function index($project_id,
                          ProjectRepository $projectRepository,
                          DegustationRepository $degustationRepository)
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);

        $degustation = $degustationRepository->findBy(['status' => [1, 2, 3, 4, 5], 'project' => $project]);

        $degustation_array = [];
        foreach ($degustation as $degustation_item) {
            $degustation_array[] = $degustation_item->getArrayParam();
        }

        $app_state = json_encode([
                'degustations' => $degustation_array,
                'project_id' => $project->getId()
            ]
        );
        return $this->render('degustation/index.html.twig', [
            'controller_name' => 'DegustationController',
            'app_state' => addslashes($app_state),
            'project' => $project
        ]);
    }

    /**
     * @Route("/project/{id}/degustation/{degustation_id}", name="degustation", requirements={"degustation_id"="\d+"})
     */
    public function show(Project $project,
                         $degustation_id,
                         ProductRepository $productRepository,
                         ProjectRepository $projectRepository,
                         CategoryRepository $categoryRepository,
                         DegustationRepository $degustationRepository)
    {

        $categories = $categoryRepository->findBy(['project' => $project, 'status' => true]);
        $categories_array = $this->make_array($categories);
        $degustation = $degustationRepository->findOneBy(['id' => $degustation_id]);

        $products = $degustation->getProducts();

        $array_products = [];
        foreach ($products as $product_item) {
            $product_arr = $product_item->getArrayParamDegust();
            $scores_arr = ['view' => ['all' => [], 'average' => null],
                'taste' => ['all' => [], 'average' => null],
                'concept' => ['all' => [], 'average' => null],
                'price' => ['all' => [], 'average' => null],
                'comment' => ['all' => []],
            ];
            foreach ($product_arr['scores'] as $score_item) {
                if ($score_item->getViewScore() != '' and $score_item->getViewScore() != null) {
                    $scores_arr['view']['all'][] = $score_item->getViewScore();
                }
                if ($score_item->getTasteScore() != '' and $score_item->getTasteScore() != null) {
                    $scores_arr['taste']['all'][] = $score_item->getTasteScore();
                }
                if ($score_item->getConceptScore() != '' and $score_item->getConceptScore() != null) {
                    $scores_arr['concept']['all'][] = $score_item->getConceptScore();
                }
                if ($score_item->getPriceScore() != '' and $score_item->getPriceScore() != null) {
                    $scores_arr['price']['all'][] = $score_item->getPriceScore();
                }
                if ($score_item->getComment() != '' and $score_item->getComment() != null) {
                    $scores_arr['comment']['all'][] = $score_item->getComment();
                }


            }
            if (count($scores_arr['view']['all']) > 0) {
                $scores_arr['view']['average'] = round(array_sum($scores_arr['view']['all']) / count($scores_arr['view']['all']));
            }
            if (count($scores_arr['taste']['all']) > 0) {
                $scores_arr['taste']['average'] = round(array_sum($scores_arr['taste']['all']) / count($scores_arr['taste']['all']));
            }
            if (count($scores_arr['concept']['all']) > 0) {
                $scores_arr['concept']['average'] = round(array_sum($scores_arr['concept']['all']) / count($scores_arr['concept']['all']));
            }
            if (count($scores_arr['price']['all']) > 0) {
                $scores_arr['price']['average'] = round(array_sum($scores_arr['price']['all']) / count($scores_arr['price']['all']));
            }

            $product_arr['scores'] = $scores_arr;
            $product_arr['photos'] = [];

            $photos = $product_item->getPhotos();

            foreach ($photos as $photo) {
                if ($photo->getStatus() == 4) {
                    $preview = $photo->getPreview();
                    $img = $photo->getImg();
                    $id = $photo->getId();
                    $type = $photo->gettype();
                    $isMain = $photo->getisMain();

                    $product_arr['photos'][] = ['preview' => $preview,
                        'img' => $img,
                        'id' => $id,
                        'isMain' => $isMain,
                        'type' => $type];
                }

            }

            $array_products[] = $product_arr;
        }


        $app_state = json_encode([
            'categories' => $categories_array,
            'project_id' => $project->getId(),
            'degustation' => ['id' => $degustation->getId(),
                'link' => 'http://' . $_SERVER['SERVER_NAME'] . '/degustation/' . $degustation->getHash(),
                'name' => $degustation->getName(),
                'date' => $degustation->getDate()->format('Y-m-d'),
                'time' => $degustation->getDate()->format('h:i'),
                'place' => $degustation->getPlace(),
                'description' => $degustation->getDescription(),
                'products' => $array_products,
            ],
        ]);

        return $this->render('degustation/show.html.twig', [
            'app_state' => addslashes($app_state),
            'project' => $project
        ]);
    }

    /**
     * @Route("/degustation/{hash}", name="vote")
     */

    public function vote($hash,
                         DegustationRepository $degustationRepository,
                         SessionInterface $session,
                         ProductRepository $productRepository,
                         DegustationScoreRepository $dsr)
    {
        $Degustation = $degustationRepository->findOneBy(['hash' => $hash]);

        $degust_user_hash = $session->get('degust_user_hash', false);



        if (!$degust_user_hash) {
            $degust_user_hash = md5(uniqid());
            $session->set('degust_user_hash', $degust_user_hash);
        }
        $scores = $dsr->findBy(['degustation_user_hash' => $degust_user_hash, 'degustation' => $Degustation]);
        $products = $Degustation->getProducts();

        $array_product = [];
        foreach ($products as $key => $product) {
            $scores_product = $product->getDegustationScores();
            $array_product[$key] = $product->getArrayParamDegust();

            $isScore = false;
            foreach ($scores_product as $score_item) {
                if ($score_item->getDegustationUserHash() == $degust_user_hash) {
                    $isScore = true;
                    $isAllScore = false;
                    if ($score_item->getViewScore() != null and $score_item->getTasteScore() != null and $score_item->getConceptScore() != null) {
                        $isAllScore = true;
                    }
                    $array_product[$key]['scores'] = ['taste' => $score_item->getTasteScore(),
                        'view' => $score_item->getViewScore(),
                        'concept' => $score_item->getConceptScore(),
                        'price' => $score_item->getPriceScore(),
                        'comment' => $score_item->getComment(),
                    ];
                    $array_product[$key]['saved'] = $isAllScore;
                    break;
                }
            }
            if (!$isScore) {
                $array_product[$key]['scores'] = ['taste' => null,
                    'view' => null,
                    'concept' => null,
                    'price' => null,
                    'comment' => null,
                ];
                $array_product[$key]['saved'] = false;
            }

            $product_arr['photos'] = [];

            $photos = $product->getPhotos();

            foreach ($photos as $photo) {
                if ($photo->getStatus() == 4) {
                    $preview = $photo->getPreview();
                    $img = $photo->getImg();
                    $id = $photo->getId();
                    $type = $photo->gettype();
                    $isMain = $photo->getisMain();

                    $array_product[$key]['photos'][] = ['preview' => $preview,
                        'img' => $img,
                        'id' => $id,
                        'isMain' => $isMain,
                        'type' => $type];
                }

            }
        }
        $app_state = json_encode([
            'products' => $array_product,
            'user_hash' => $degust_user_hash,
            'degustation_hash' => $hash
        ]);

        return $this->render('degustation/vote.html.twig', [
            'app_state' => addslashes($app_state),
        ]);


    }

    /**
     * @Route("ajax/degustation/vote/{hash}", name="update_vote")
     */
    public function update_vote($hash,
                                Request $request,
                                ProjectRepository $projectRepository,
                                ProductRepository $productRepository,
                                DegustationRepository $degustationRepository,
                                DegustationScoreRepository $dsr,
                                CategoryRepository $categoryRepository)
    {
        $Degustation = $degustationRepository->findOneBy(['hash' => $hash]);

        $products_info = json_decode($request->request->get('products'));
        $user_hash = $request->request->get('hash');

        $scores = $dsr->findBy(['degustation_user_hash' => $user_hash, 'degustation' => $Degustation]);

        $entityManager = $this->getDoctrine()->getManager();

        $products_array = json_decode(json_encode($products_info), true);

        foreach ($products_array as $key => $product_item) {
            $isScore = false;

            $isAllScore = false;
            $product = $productRepository->findOneBy(['id' => $product_item['id']]);
            foreach ($scores as $score_item) {
                if ($product_item['id'] == $score_item->getProduct()->getId()) {
                    $isScore = true;

                    $score = $score_item;
                    break;
                }
            }

            if (!$isScore) {
                $score = new DegustationScore();
            }

            if (isset($product_item['scores']['view'])) {
                $score->setViewScore($product_item['scores']['view']);
                $isView = true;
            } else {
                $isView = false;
            }
            if (isset($product_item['scores']['taste'])) {
                $score->setTasteScore($product_item['scores']['taste']);
                $isTaste = true;
            } else {
                $isTaste = false;
            }
            if (isset($product_item['scores']['concept'])) {
                $score->setConceptScore($product_item['scores']['concept']);
                $isConcept = true;
            } else {
                $isConcept = false;
            }
            if (isset($product_item['scores']['price'])) {
                $score->setPriceScore($product_item['scores']['price']);

            }
            if (isset($product_item['scores']['comment'])) {
                $score->setComment($product_item['scores']['comment']);

            }

            $score->setProduct($product);
            $score->setDateUpdate(new \DateTime('now'));
            $score->setDegustationUserHash($user_hash);
            $score->setDegustation($Degustation);

            $entityManager->persist($score);

            if ($isView and $isTaste and $isConcept) {
                $isAllScore = true;
            }
            $products_array[$key]['saved'] = $isAllScore;
        }

        $entityManager->flush();


        $app_state = json_encode([
            'products' => $products_array,
        ]);

        $response = JsonResponse::fromJsonString($app_state);

        return $response;

    }

    /**
     * @Route("/project/{project_id}/degustation/new", name="new_degustation")
     */
    public function new($project_id,
                        ProjectRepository $projectRepository,
                        CategoryRepository $categoryRepository)
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);

        $categories = $categoryRepository->findBy(['project' => $project, 'status' => true]);

        $categories_array = $this->make_array($categories);

        $app_state = json_encode([
            'categories' => $categories_array,
            'project_id' => $project->getId(),
            'degustation' => ['id' => null,
                'name' => '',
                'date' => null,
                'time' => null,
                'place' => null,
                'description' => null,
                'products' => [],
                'link' => false
            ],
        ]);
        return $this->render('degustation/new.html.twig', [
            'app_state' => addslashes($app_state),
            'project' => $project
        ]);
    }

    /**
     * @Route("/ajax/{id}/degustation/create", name="create_degustation")
     */
    public function createDegustation(Project $project,
                                      Request $request,
                                      ProjectRepository $projectRepository,
                                      ProductRepository $productRepository,
                                      CategoryRepository $categoryRepository,
                                      DegustationRepository $degustationRepository)
    {


        $entityManager = $this->getDoctrine()->getManager();
        $arr_upated = $this->updateFunction($project,
            $request,
            $projectRepository,
            $productRepository,
            $categoryRepository,
            $degustationRepository);

        $degustation = $arr_upated['degustation'];
        $degustation->setStatus(2);
        $app_state = json_encode([
            'products' => $arr_upated['products_array'],
            'degustation' => $arr_upated['degustation_array'],
        ]);

        $entityManager->persist($degustation);
        $entityManager->flush();
        $response = JsonResponse::fromJsonString($app_state);

        return $response;

    }

    /**
     * @Route("/ajax/{id}/degustation/update", name="update_degustation")
     */
    public function updateDegustation(Project $project,
                                      Request $request,
                                      ProjectRepository $projectRepository,
                                      ProductRepository $productRepository,
                                      CategoryRepository $categoryRepository,
                                      DegustationRepository $degustationRepository)
    {

        $arr_upated = $this->updateFunction($project,
            $request,
            $projectRepository,
            $productRepository,
            $categoryRepository,
            $degustationRepository);

        $app_state = json_encode([
            'products' => $arr_upated['products_array'],
            'degustation' => $arr_upated['degustation_array'],
        ]);

        $response = JsonResponse::fromJsonString($app_state);

        return $response;

    }


    /**
     * @Route("/ajax/project/{project_id}/degustation/add_product", name="ajax_add_product")
     */
    public function addProduct($project_id,
                               ProjectRepository $projectRepository,
                               DegustationRepository $degustationRepository)
    {

        $session = new Session();
//        if (isset($session->get('create_degudtation'))){
//            $degustation_id = $session->get('create_degudtation_id');
//            $degustation = $degustationRepository->findBy(['id'=>$degustation_id]);
//        } else {
//            $degustation = new Degustation();
//        }


        $project = $projectRepository->findOneBy(['id' => $project_id]);
        return $this->render('degustation/new.html.twig', [
            'controller_name' => 'DegustationController',
            'project' => $project
        ]);
    }


    public function updateFunction(Project $project,
                                   Request $request,
                                   ProjectRepository $projectRepository,
                                   ProductRepository $productRepository,
                                   CategoryRepository $categoryRepository,
                                   DegustationRepository $degustationRepository)
    {

        $degustation_info = json_decode($request->request->get('degustation'));
        $products = $degustation_info->products;

        $entityManager = $this->getDoctrine()->getManager();

        if ($degustation_info->id == null) {
            $degustation = new Degustation();


        } else {
            $degustation = $degustationRepository->findOneBy(['id' => $degustation_info->id]);
        }

        $degustation->setName($degustation_info->name);
        $degustation->setDescription($degustation_info->description);
        $degustation->setPlace($degustation_info->place);
        $degustation->setproject($project);
        $date = $degustation_info->date;
        $time = $degustation_info->time;
        $date_time = new \DateTime($date . ' ' . $time);
        $degustation->setDate($date_time);


        $degust_info_array = $array = json_decode(json_encode($degustation_info), true);

        $products_array = $array = json_decode(json_encode($products), true);


        $old_products = $degustation->getProducts();

        $chech_product_array = [];
        foreach ($old_products as $old_product_item) {
            $chech_product_array[] = $old_product_item->getId();

            //
        }
        $entityManager->persist($degustation);
        $entityManager->flush();


        $degust_info_array['id'] = $degustation->getId();
        $degust_info_array['link'] = 'http://' . $_SERVER['SERVER_NAME'] . '/degustation/' . $degustation->getHash();


        foreach ($products as $key => $product_item) {
            if (!isset($product_item->id)) {
                $product = new Product();

            } else {
                $product = $productRepository->findOneBy(['id' => $product_item->id]);
            }
            $product->setNameWork($product_item->name);
            $product->setOldStatus(1);

            $category = $categoryRepository->findOneBy(['id' => $product_item->category->id]);
            $product->setCategory($category);

            $product->setConsist($product_item->consist);


            $weight = preg_replace("/^(?:\d+\,)+\d?$/", '', $product_item->weight);

            $product->setWeight($weight);
            $cost_price = preg_replace("/^(?:\d+\,)+\d?$/", '', $product_item->cost_price);

            $product->setCostPrice($cost_price);
            $product->setPovar($product_item->povar);
            $product->setProject($project);

            $entityManager->persist($product);

            $entityManager->flush();

            $degustation->addProduct($product);

            foreach ($chech_product_array as $key => $id) {
                if ($id == $product->getId()) {
                    unset($chech_product_array[$key]);
                }
            }

            $products_array[$key]['id'] = $product->getId();
        }

        $deleted_products = $productRepository->findBy(['id' => $chech_product_array]);
        foreach ($deleted_products as $key => $deleted_product) {
            $degustation->removeProduct($deleted_product);
        }

        $entityManager->persist($degustation);

        $entityManager->flush();

        return ['products_array' => $products_array,
            'degustation_array' => $degust_info_array,
            'degustation' => $degustation];
    }

    /**
     * @Route("/ajax/{id}/degustation/update_status", name="update_degustation_status", methods={"GET"})
     */
    public function update_status(Project $project,
                                  Request $request,
                                  DegustationRepository $degustationRepository)
    {
        $degustation_id = $request->query->get('degustation_id');

        $status = $request->query->get('status');

        $entityManager = $this->getDoctrine()->getManager();

        $degustation = $degustationRepository->findOneBy(['id' => $degustation_id]);

        $degustation->setStatus($status);

        $entityManager->persist($degustation);

        $entityManager->flush();

        return new Response(1);
    }

}

