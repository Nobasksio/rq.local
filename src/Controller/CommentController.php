<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/ajax/{id}/comment/", name="add_comment", methods={"POST"})
     */
    public function add_comment(Product $product, Request $request)
    {

        $data = $request->request->get('data');
        $data = json_decode(json_encode(json_decode($data)), true);

        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        $comment = new Comment();

        if (!isset($data['place'])){
            $data['place'] = 0;
        }

        $comment->setUser($user);
        $comment->setProduct($product);
        $comment->setPlace($data['place']);
        $comment->setText($data['text']);

        $entityManager->persist($comment);
        $entityManager->flush();

        $response = JsonResponse::fromJsonString(1);
        return $response;
    }
}
