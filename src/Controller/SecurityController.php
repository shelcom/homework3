<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    /**
     * @Route("/home", name="home")
     */
    public function homeAction()
    {
        return $this->render('base.html.twig', [
            'message' => 'Welcome'
        ]);
    }
    /**
     * @Route("/article/home")
     */
    public function articleAction()
    {
        return $this->render('base.html.twig', [
            'message' => 'Welcome'
        ]);
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->render('base.html.twig', [
            'message' => 'Welcome admin'
        ]);
    }
}
