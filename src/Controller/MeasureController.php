<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MeasureController extends AbstractController
{
    /**
     * @Route("/measure", name="measure")
     */
    public function index()
    {
        return $this->render('measure/index.html.twig', [
            'controller_name' => 'MeasureController',
        ]);
    }
}
