<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 07/04/2019
 * Time: 13:01
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request,ObjectManager $manager){

        $user = new User();
        $form = $this->createForm(
            UserType::class,
            $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $password = $passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($password);
            $user->setStatus(true);
            $user->setDateCreate(new \DateTime('now'));

            $manager->persist($user);
            $manager->flush();

            return $this->redirect('/login');
        }

        return $this->render('register/register.html.twig',[
            'form' => $form->createView()
        ]);


    }

}