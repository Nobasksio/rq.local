<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 01/07/2019
 * Time: 15:02
 */

namespace App\Controller;


use App\Entity\Photo;
use App\Repository\PhotoRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends BaseController
{

    /**
     * @Route("/ajax/photo/make_main/{photo}", name="make_main_photo")
     */
    public function index(Photo $photo, PhotoRepository $photoRepository)
    {

        $product = $photo->getProduct();

        $entityManager = $this->getDoctrine()->getManager();

        $old_main_photo = $photoRepository->findOneBy(['product'=>$product,'isMain'=>true]);

        if ($old_main_photo) {
            $old_main_photo->setisMain(false);
            $entityManager->persist($old_main_photo);
        }


        $photo->setisMain(true);


        $entityManager->persist($photo);
        $entityManager->flush();


        return new Response(1);

    }

}