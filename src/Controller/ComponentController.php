<?php

namespace App\Controller;

use App\Entity\Component;
use App\Entity\Measure;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComponentController extends AbstractController
{
    /**
     * @Route("/component", name="component")
     */
    public function index()
    {
        return $this->render('component/index.html.twig', [
            'controller_name' => 'ComponentController',
        ]);
    }

    /**
     * @Route("ajax/{id}/component/create")
     */
    public function componentCreateAjax(Project $project,Request $request){

        $component_name = $request->query->get('name');

        $new_component = new Component();

        $new_component->setName($component_name);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($new_component);
        $entityManager->flush();

        $category_id = $new_component->getId();

        return new Response($category_id);

    }
    /**
     * @Route("ajax/{id}/measure/create")
     */
    public function measureCreateAjax(Project $project,Request $request){

        $measure_name = $request->query->get('name');

        $new_measure = new Measure();

        $new_measure->setName($measure_name);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($new_measure);
        $entityManager->flush();

        $measure_id = $new_measure->getId();

        return new Response($measure_id);


    }

}
