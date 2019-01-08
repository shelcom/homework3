<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

        /**
         * @Route("/login", name="app_login")
         */
        public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        $user = new User();
        $user->setEmail($authenticationUtils->getLastUsername());

        $form = $this->createForm(LoginType::class, $user, [
            'action' => $this->generateUrl('login_check')
        ]);

        if ($error = $authenticationUtils->getLastAuthenticationError()) {
            $this->addFlash('message', $error->getMessage());

        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    /**
     * @Route("/profile", name="profile")
     */
    public function profile()
    {

        return $this->render('blog/profile.html.twig', [
            
        ]);
    }
    
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }


}
