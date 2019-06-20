<?php

namespace App\Controller;

use App\Entity\IikoCategory;
use App\Entity\Project;
use App\Form\NewProjectType;
use App\Form\ProjectType;
use App\Repository\DepartmentRepository;
use App\Repository\IikoCategoryRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project_index", methods={"GET"})
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        $user = $this->getUser();
        $projects = $user->getProjects();

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/summary", name="summary", methods={"GET"})
     */
    public function show(Project $project): Response
    {

        //todo сделать проверку прав на текущий проект

        $user_arr = $project->getUsers()->toArray();
        $user_id = $this->getUser()->getId();
        $arr_acces = array();
        foreach($user_arr as $user_item){

            $arr_acces[] = $user_item->getId();

        }

        if (!in_array($user_id,$arr_acces)){
            return $this->redirectToRoute('project_index');
        }


        return $this->render('project/summary.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,
                         Project $project,
                         DepartmentRepository $drepository,
                         IikoCategoryRepository $iikoCategoryRepository): Response
    {

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_index', [
                'id' => $project->getId(),
            ]);
        }

        $departments = $drepository->findBy(['type'=>'DEPARTMENT']);

        $array_categories = [];

        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $departments_json = $serializer->serialize($departments, 'json', ['attributes' => ['id', 'name']]);

        $project_json = $serializer->serialize($project, 'json', ['attributes' => ['id', 'name']]);



        $app_state = array();

        $departments_array = array();
        $project_departments = [];
        $iiko_categories_arr = [];



        foreach ($departments as $department){
            $departments_array[] = array('id'=>$department->getId(),
                'name'=>$department->getName());
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

        foreach ($array_categories as $iikoCategory){
            $iiko_categories_arr[] = ['id'=>$iikoCategory->getId(),
                'name'=>$iikoCategory->getName()
            ];
        }
        $check_array = [];
        foreach($iiko_categories_arr as $key => $item){
            if (!in_array($item['id'], $check_array)){
                $check_array[] = $item['id'];
            } else {
                unset($iiko_categories_arr[$key]);
            }

        }
        $iiko_categories_arr = array_values($iiko_categories_arr);

        $app_state = json_encode(array('project_id'=>$project->getId(),
            'categories'=>$iiko_categories_arr,
            'project_departments' =>$project_departments,
            'departments'=>$departments_array)
        );


        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'app_state' => $app_state
        ]);
    }
    /**
     * @Route("/ajax/{id}/update", name="getDepartments")
     */
    public function getDepartments(Request $request,Project $project, DepartmentRepository $drepository): Response
    {

        $departments = $request->query->get('departments');

        $departments = json_decode($departments);
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($project->getIikoDepartment() as $department_item) {
            $project->removeIikoDepartment($department_item);

        }
        $entityManager->persist($project);
        $entityManager->flush();

        foreach ($departments as $department_item) {
            $department = $drepository->findOneBy(['id'=> $department_item->id]);
            $project->addIikoDepartment($department);

            $entityManager->persist($project);
        }

        $entityManager->flush();

        return new Response('1');
    }


    /**
     * @Route("/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index');
    }




}
