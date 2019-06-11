<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProjectRepository;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController0 extends AbstractController
{


    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository) {

        $this->projectRepository = $projectRepository;

    }

    /**
     * @Route("/project", name="project")
     */
    public function index()
    {

        return $this->render('project/index.html.twig', [
            'projects' => $this->projectRepository->findAll()
        ]);


    }
}
