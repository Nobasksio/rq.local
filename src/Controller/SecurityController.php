<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 06/04/2019
 * Time: 21:50
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils){
        return $this->render('auth/index.html.twig',[
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'=>$authenticationUtils->getLastAuthenticationError()]);
    }
    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){
        return $this->render('auth/index.html.twig');
    }
}