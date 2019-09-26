<?php

namespace App\Controller;

use App\Entity\Plate;
use App\Entity\Project;
use App\Repository\CategoryRepository;
use App\Repository\PlateRepository;
use App\Repository\ProductRepository;
use App\Utility\FirstProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlateController extends BaseController
{
    /**
     * @Route("/project/{id}/plate", name="plate")
     */
    public function index(Project $project,
                          FirstProduct $firstProduct,
                          ProductRepository $productRepository,
                          PlateRepository $plateRepository,
                          CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findBy(['project' => $project, 'status' => true]);
        if (isset($categories[0])) {
            $products = $productRepository->findBy(['category' => $categories, 'old_status' => 2]);
        } else {
            $products = $productRepository->findBy(['project' => $project, 'old_status' => 2]);
        }
        $categories_array = $this->make_array($categories);
        $products_array = [];
        foreach ($products as $product_item) {
            $products_array[] = $firstProduct->getProductInfoArray($product_item);
        }

        $plates = $project->getPlates();

        $plates = $this->make_array($plates);

        $app_state = json_encode([
            'products' => $products_array,
            'categories' => $categories_array,
            'products' => $products_array,
            'plates'=> $plates,
            'project_id' => $project->getId()

        ]);

        return $this->render('plate/index.html.twig', [
            'controller_name' => 'PlateController',
            'project' => $project,
            'app_state' => $app_state
        ]);
    }

    /**
     * @Route("/ajax/project/{id}/plate", name="plate_create", methods={"POST"})
     */
    public
    function uploadFile(Project $project,
                        Request $request,
                        \App\Utility\FileUploader $fileUploader)
    {
        $file = $request->files->get('file');

        $user = $this->getUser();
        $fileName = $fileUploader->upload($file);

        $entityManager = $this->getDoctrine()->getManager();
        $plate = new Plate();
        $plate->setFull($fileName['img']);
        $plate->setPreview($fileName['preview']);
        $plate->setUserCreate($user);

        $entityManager->persist($plate);
        $entityManager->flush();

        $project->addPlate($plate);

        $entityManager->persist($project);
        $entityManager->flush();

        $array_new_photo = ['full' => $fileName['img'],
            'preview' => $fileName['preview'],
            'size'=>$plate->getSize(),
            'id' => $plate->getId(),
            'name'=>$plate->getName()];

        $json_respoonse = json_encode($array_new_photo);
        $response = JsonResponse::fromJsonString($json_respoonse);
        return $response;

    }
}
