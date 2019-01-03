<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function article(Request $request)
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            return $this->redirectToRoute('article');

        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
