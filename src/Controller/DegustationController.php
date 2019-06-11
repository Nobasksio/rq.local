<?php

namespace App\Controller;

use App\Entity\Degustation;
use App\Repository\DegustationRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class DegustationController extends AbstractController
{
    /**
     * @Route("/project/{project_id}/degustation", name="degustation")
     */
    public function index($project_id, ProjectRepository $projectRepository)
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        return $this->render('degustation/index.html.twig', [
            'controller_name' => 'DegustationController',
            'project'=>$project
        ]);
    }
    /**
     * @Route("/project/{project_id}/degustation/new", name="new_degustation")
     */
    public function new($project_id, ProjectRepository $projectRepository)
    {
        $project = $projectRepository->findOneBy(['id' => $project_id]);
        return $this->render('degustation/new.html.twig', [
            'controller_name' => 'DegustationController',
            'project'=>$project
        ]);
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
            'project'=>$project
        ]);
    }
}
