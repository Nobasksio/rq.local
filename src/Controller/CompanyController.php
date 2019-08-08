<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company")
     */
    public function index()
    {
        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }

    /**
     * @Route("/company/create", name="company_create")
     */
    public function create(ProjectRepository $projectRepository)
    {
        $user = $this->getUser();

        $projects = $user->getProjects();

        $all_projects = [];

        foreach ($projects as $project_item){
            $all_projects[] = ['id' => $project_item->getId(),
                'value' => $project_item->getId(),
                'name' => $project_item->getProjectName(),
                'text' => $project_item->getProjectName()
            ];
        }
        $app_state = json_encode([
            'all_projects' => $all_projects,
            'company' => [
                'id' => 0,
                'name' => null,
                'projects_company' => [],
                'iiko_address' => null,
                'iiko_user' => null,
                'iiko_pass' => null,
                'iiko_pass_hash' => null,
                'isDepartment' => true,
                'isProduct' => true,
                'isTtk' => true
            ]
        ]);

        return $this->render('company/edit.html.twig', [
            'controller_name' => 'Создание компании',
            'app_state' => $app_state
        ]);
    }

    /**
     * @Route("/ajax/company/update", name="company_update_ajax", methods={"POST"})
     */
    public function update_ajax(Request $request,
                                ProjectRepository $projectRepository,
                                EntityManagerInterface $em,
                                CompanyRepository $companyRepository)
    {

        $company_info = json_decode($request->request->get('company'));

        $company_array = json_decode(json_encode($company_info), true);

        if ($company_array['id'] != 0) {
            $Company = $companyRepository->findOneBy(['id' => $company_array['id']]);
        } else {
            $Company = new Company();
        }

        $Company->setName($company_array['name']);

        $Company->setIsDepartment($company_array['isDepartment']);
        $Company->setIsProduct($company_array['isProduct']);
        $Company->setIsTtk($company_array['isTtk']);


        $Company = $this->updateCollection($Company, $company_array['projects_company'], 'project', $projectRepository, $em);


        $Company->setIikoUser($company_array['iiko_user']);

        $Company->setIikoPassHash($company_array['iiko_pass']);

        $Company->setIikoAddress($company_array['iiko_address']);
        $user = $this->getUser();
        $Company->setCreatedBy($user);
        $em->flush();

        $company_array['id'] = $Company->getId();
        $app_state = json_encode([
            'company' => $company_array
        ]);

        $response = JsonResponse::fromJsonString($app_state);

        return $response;

    }

    function updateCollection($entity,
                              $array_collection,
                              $name_field,
                              $entity_repository,
                              EntityManagerInterface $em)
    {

        $name_getter = 'get' . ucfirst($name_field) . 's';
        $collection = $entity->$name_getter();

        $check_array = [];
        foreach ($collection as $item) {
            $check_array[$item->getId()] = $item;
        }
        foreach ($array_collection as $key => $collection_item) {

            if (gettype($entity) == gettype($collection_item)) {

            } else {
                $name_adder = 'add' . ucfirst($name_field);
                $addEntity = $entity_repository->findOneBy(['id' => $collection_item['id']]);
                $entity->$name_adder($addEntity);
                unset($check_array[$collection_item['id']]);
            }
        }

        foreach ($check_array as $delete_entity) {
            $name_remover = 'remove' . ucfirst($name_field);
            $entity->$name_remover($delete_entity);
            unset($check_array[$collection_item['id']]);

        }
        $em->persist($entity);

        return $entity;

    }
}
