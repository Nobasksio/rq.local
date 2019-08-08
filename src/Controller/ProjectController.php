<?php

namespace App\Controller;

use App\Entity\IikoCategory;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\UserProjectRole;
use App\Form\NewProjectType;
use App\Form\ProjectType;
use App\Repository\CategoryRepository;
use App\Repository\DepartmentRepository;
use App\Repository\IikoCategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserProjectRoleRepository;
use App\Repository\UserRepository;
use App\Utility\FirstProduct;
use App\Utility\AnaliticsUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/project")
 */
class ProjectController extends BaseController
{
    /**
     * @Route("/", name="project_index", methods={"GET"})
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        $user = $this->getUser();
        $projects = $user->getProjects();

        $company_arr = [];
        $free_arr = [];

        $uniq_company_arr = [];
        // start bad solution sort project
        foreach ($projects as $project_item) {
            $company = $project_item->getCompany();
            if ($company) {
                $company_id = $company->getId();

                if (!in_array($company_id, $uniq_company_arr)) {
                    $uniq_arr[] = $company_id;
                    $company_arr[] = ['id' => $company_id, 'name' => $company->getName()];
                }
                if (!$project_item->getIsDelete()) {
                    $projects_arr[] = [
                        'company_id' => $company_id,
                        'id' => $project_item->getId(),
                        'name' => $project_item->getProjectName()

                    ];
                }
            } else {
                if (!$project_item->getIsDelete()) {
                    $projects_arr[] = [
                        'company_id' => 0,
                        'id' => $project_item->getId(),
                        'name' => $project_item->getProjectName(),
                    ];
                }
            }
        }


        // end bad solution sort project

        $app_state = json_encode([
            'projects' => $projects_arr,
            'companies' => $company_arr
        ]);


        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'app_state' => $app_state
        ]);
    }


    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request, DepartmentRepository $drepository): Response
    {
        $project = new Project();
        $form = $this->createForm(NewProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $entityManager = $this->getDoctrine()->getManager();
            $project->addUser($user);
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_index');
        }

        $user = $this->getUser();

        $projects = $user->getProjects();
        $company = $project->getCompany();

        $uniq_users = ['id' => [], 'user' => []];
        foreach ($projects as $project_item) {
            foreach ($project_item->getUsers() as $user_item) {
                if (!in_array($user_item->getId(), $uniq_users['id'])) {
                    $uniq_users['id'][] = $user_item->getId();
                    $uniq_users['user'][] = [
                        'id' => $user_item->getId(),
                        'name' => $user_item->getName(),
                        'mail' => $user_item->getEmail()
                    ];
                }
            }
        }

        $departments = $drepository->findBy(['type' => 'DEPARTMENT']);
        $departments_array = array();

        foreach ($departments as $department) {
            $departments_array[] = array('id' => $department->getId(),
                'name' => $department->getName());
        }


        $app_state = json_encode(array('project_id' => 0,
                'project' => ['project_id' => 0,
                    'name' => null,
                    'type' => null,
                    'date' => null,
                    'marketolog' => [],
                    'cooker' => [],
                    'accountant' => [],
                    'admin' => []
                ],
                'all_users' => $uniq_users['user'],
                'project_departments' => [],
                'departments' => $departments_array)
        );

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'app_state' => $app_state
        ]);
    }

    /**
     * @Route("/{id}/summary", name="summary", methods={"GET"})
     */
    public function show(Project $project,
                         FirstProduct $firstProduct,
                         ProductRepository $productRepository,
                         CategoryRepository $categoryRepository): Response
    {

        //todo сделать проверку прав на текущий проект


        $user_arr = $project->getUsers()->toArray();
        $user_id = $this->getUser()->getId();
        $arr_acces = array();

        $categories = $categoryRepository->findBy(['project' => $project, 'status' => true]);
        if (isset($categories[0])) {
            $products = $productRepository->findBy(['category' => $categories[0], 'old_status' => 2]);
        } else {
            $products = $productRepository->findBy(['project' => $project, 'old_status' => 2]);
        }
        $categories_array = $this->make_array($categories);
        $products_array = [];
        foreach ($products as $product_item) {
            $products_array[] = $firstProduct->getProductInfoArray($product_item);
        }


        foreach ($user_arr as $user_item) {

            $arr_acces[] = $user_item->getId();

        }

        if (!in_array($user_id, $arr_acces)) {
            return $this->redirectToRoute('project_index');
        }

        if (isset($categories[0])) {
            $selected_categoory = $categories[0]->getId();
            $f_product = $firstProduct->getFirstProduct($categories[0]);
        } else {
            $selected_categoory = 0;
            $f_product = [];
//            $f_product = $firstProduct->getProductInfoArray($products[0]);
        }

        $app_state = json_encode([
            'product' => $f_product,
            'products' => $products_array,
            'categories' => $categories_array,
            'selected_categoory' => $selected_categoory,
            'products' => $products_array,
            'project_id' => $project->getId()

        ]);

        return $this->render('project/summary.html.twig', [
            'project' => $project,
            'app_state' => $app_state
        ]);
    }


    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,
                         Project $project,
                         DepartmentRepository $drepository,
                         UserProjectRoleRepository $userProjectRoleRepository,
                         IikoCategoryRepository $iikoCategoryRepository): Response
    {

        $departments = $drepository->findBy(['type' => 'DEPARTMENT']);

        $array_categories = [];

        $app_state = array();

        $departments_array = array();
        $project_departments = [];
        $iiko_categories_arr = [];


        foreach ($departments as $department) {
            $departments_array[] = array('id' => $department->getId(),
                'name' => $department->getName());
        }
        foreach ($project->getIikoDepartment() as $iiko_department) {
            $project_departments[] = array('id' => $iiko_department->getId(),
                'name' => $iiko_department->getName());

            //выбор категорий только привязанных к проектам
//            if (count($iiko_department->getIikoCategories()) > 0) {
//                foreach ($iiko_department->getIikoCategories() as $category) {
//                    $array_categories[] = $category;
//                }
//            }
        }

        $chosen_category = [];
        foreach ($project->getIikoCategories() as $iiko_category){
            $chosen_category[] = ['id' => $iiko_category->getId(),
                'name' => $iiko_category->getName()];

        }

        //выбор всех категорий
        $array_categories = $iikoCategoryRepository->findAll();

        foreach ($array_categories as $iikoCategory) {
            $iiko_categories_arr[] = ['id' => $iikoCategory->getId(),
                'name' => $iikoCategory->getName()
            ];
        }
        //отключил потому что вывожу все категории iiko
//        $check_array = [];
//        foreach ($iiko_categories_arr as $key => $item) {
//            if (!in_array($item['id'], $check_array)) {
//                $check_array[] = $item['id'];
//            } else {
//                unset($iiko_categories_arr[$key]);
//            }
//        }
        $iiko_categories_arr = array_values($iiko_categories_arr);


        $company = $project->getCompany();

        $uniq_users = ['id' => [], 'user' => []];
        if ($company) {
            $Company_all_projects = $company->getProjects();


            foreach ($Company_all_projects as $project_item) {
                foreach ($project_item->getUsers() as $user_item) {
                    if (!in_array($user_item->getId(), $uniq_users['id'])) {
                        $uniq_users['id'][] = $user_item->getId();
                        $uniq_users['user'][] = [
                            'id' => $user_item->getId(),
                            'name' => $user_item->getName(),
                            'mail' => $user_item->getEmail()
                        ];
                    }
                }
            }
        } else {
            foreach ($project->getUsers() as $user_item) {
                if (!in_array($user_item->getId(), $uniq_users['id'])) {
                    $uniq_users['id'][] = $user_item->getId();
                    $uniq_users['user'][] = [
                        'id' => $user_item->getId(),
                        'name' => $user_item->getName(),
                        'mail' => $user_item->getEmail()
                    ];
                }
            }
        }

        $user_rolls = $userProjectRoleRepository->findBy(['Project' => $project]);

        $admin_array = [];
        $marketolog_array = [];
        $cooker_array = [];
        $accountant_array = [];
        $barmanager_array = [];

        foreach ($user_rolls as $user_role) {


            $role = $user_role->getRole();
            if ($role == 2) {
                $cooker_array[] = [
                    'id' => $user_role->getUser()->getId(),
                    'name' => $user_role->getUser()->getName(),
                    'mail' => $user_role->getUser()->getEmail(),
                ];
            } elseif ($role == 3) {
                $accountant_array[] = [
                    'id' => $user_role->getUser()->getId(),
                    'name' => $user_role->getUser()->getName(),
                    'mail' => $user_role->getUser()->getEmail(),
                ];
            } elseif ($role == 4) {
                $marketolog_array[] = [
                    'id' => $user_role->getUser()->getId(),
                    'name' => $user_role->getUser()->getName(),
                    'mail' => $user_role->getUser()->getEmail(),
                ];
            } elseif ($role == 5) {
                $barmanager_array[] = [
                    'id' => $user_role->getUser()->getId(),
                    'name' => $user_role->getUser()->getName(),
                    'mail' => $user_role->getUser()->getEmail(),
                ];
            } elseif ($role == 1) {
                $admin_array[] = [
                    'id' => $user_role->getUser()->getId(),
                    'name' => $user_role->getUser()->getName(),
                    'mail' => $user_role->getUser()->getEmail(),
                ];
            }
        }
        $app_state = json_encode(array('project_id' => $project->getId(),
                'project' => ['project_id' => $project->getId(),
                    'name' => $project->getProjectName(),
                    'type' => $project->getKitchenType(),
                    'date' => $project->getDateCreate()->format('Y-m-d'),
                    'marketolog' => $marketolog_array,
                    'cooker' => $cooker_array,
                    'accountant' => $accountant_array,
                    'admin' => $admin_array
                ],
                'all_users' => $uniq_users['user'],
                'categories' => $iiko_categories_arr,
                'project_departments' => $project_departments,
                'chosen_category' => $chosen_category,
                'departments' => $departments_array)
        );


        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'app_state' => $app_state
        ]);
    }

    /**
     * @Route("/ajax/{project_id}/update/", name="updateProject", methods={"POST"})
     */
    public function updateProject(Request $request,
                                  $project_id,
                                  ProjectRepository $projectRepository,
                                  DepartmentRepository $drepository,
                                  UserRepository $userRepository,
                                  IikoCategoryRepository $iikoCategoryRepository,
                                  UserProjectRoleRepository $userProjectRoleRepository): Response
    {

        $data = $request->request->get('data');

        if ($project_id==0){
            $project = new Project();
            $user = $this->getUser();
            $project->addUser($user);

        } else {
            $project = $projectRepository->find($project_id);
        }


        $data_array = json_decode(json_encode(json_decode($data)), true);


        $project->setProjectName($data_array['project']['name']);
        $project->setKitchenType($data_array['project']['type']);

        $departments = json_decode($data)->departments;
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($project->getIikoDepartment() as $department_item) {
            $project->removeIikoDepartment($department_item);

        }
        $entityManager->persist($project);
        $entityManager->flush();

        foreach ($departments as $department_item) {
            $department = $drepository->findOneBy(['id' => $department_item->id]);
            $project->addIikoDepartment($department);

            $entityManager->persist($project);
        }

        $chosen_category = json_decode($data)->chosen_category;
        foreach ($project->getIikoCategories() as $category_item) {
            $project->removeIikoCategory($category_item);

        }
        $entityManager->persist($project);
        $entityManager->flush();

        foreach ($chosen_category as $category_item) {
            $category = $iikoCategoryRepository->findOneBy(['id' => $category_item->id]);
            $project->addIikoCategory($category);

            $entityManager->persist($project);
        }




        $this->updateRoll($data_array['project']['cooker'], 2, $project, $userRepository, $userProjectRoleRepository, $entityManager);
        $this->updateRoll($data_array['project']['accountant'], 3, $project, $userRepository, $userProjectRoleRepository, $entityManager);
        $this->updateRoll($data_array['project']['marketolog'], 4, $project, $userRepository, $userProjectRoleRepository, $entityManager);

        $app_state = json_encode(array(
            'project_id' => $project->getId()

        ));

        $response = JsonResponse::fromJsonString($app_state);
        return $response;
    }

    /**
     * @Route("/ajax/{project_id}/add_user/", name="invite_user", methods={"POST"})
     */
    public function add_user(Request $request,
                             $project_id,
                             ProjectRepository $projectRepository,
                             UserPasswordEncoderInterface $encoder,
                             UserRepository $userRepository): Response
    {

        $data = json_decode($request->request->get('data'));


        $data_array = json_decode(json_encode($data), true);
        $entityManager = $this->getDoctrine()->getManager();

        $New_user = $userRepository->findOneBy(['email' => $data_array['user']['mail']]);

        if (!$New_user) {
            $New_user = new User();
            $New_user->setName('Новый пользователь');
            $New_user->setEmail($data_array['user']['mail']);
            $New_user->setDateCreate(new \DateTime('now'));

            $plainPassword = '12345';
            $encoded = $encoder->encodePassword($New_user, $plainPassword);
            $New_user->setPassword($encoded);
            $New_user->setStatus(true);

            $entityManager->persist($New_user);
            $entityManager->flush();

            if ($project_id != 0){
                $project = $projectRepository->find($project_id);
                $project->addUser($New_user);
                $entityManager->persist($project);

            }

            $entityManager->flush();
        }


        $app_state = json_encode(array(
            'user' => ['id' => $New_user->getId(),
                'mail' => $New_user->getEmail(),
                'name' => $New_user->getName(),
            ],

        ));

        $response = JsonResponse::fromJsonString($app_state);
        return $response;
    }


    /**
     * @Route("/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index');
    }

    /**
     * @Route("/ajax/{id}", name="project_delete_ajax", methods={"DELETE"})
     */
    public function delete_ajax(Request $request, Project $project): Response
    {
        $project->setIsDelete(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($project);
        $entityManager->flush();


        return new Response('1');
    }

    public function updateRoll($array, $role,
                               $project,
                               UserRepository $userRepository,
                               UserProjectRoleRepository $userProjectRoleRepository,
                               $entityManager)
    {

        $past_user_role = $userProjectRoleRepository->findBy(['Project' => $project, 'role' => $role]);
        foreach ($array as $item) {
            $user = $userRepository->findOneBy(['id' => $item['id']]);
            $user_role = $userProjectRoleRepository->findOneBy(['Project' => $project, 'User' => $user, 'role' => $role]);

            if (!$user_role) {
                $user_role = new UserProjectRole();
                $user_role->setProject($project);
                $user_role->setRole($role);
                $user_role->setUser($user);

                $entityManager->persist($user_role);
            }

            foreach ($past_user_role as $key => $past_user) {
                if ($past_user->getUser()->getId() == $user->getId()) {
                    unset($past_user_role[$key]);
                }
            }
        }

        foreach ($past_user_role as $past_user) {
            $entityManager->remove($past_user);

        }

        $entityManager->flush();

        return true;

    }


}
