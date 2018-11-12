<?php

namespace App\Controller;

use App\Entity\Article;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="article", methods={"GET"})
     */
    public function showAction()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        return $this->render('blog/articles.html.twig', [
            'articles' => $articles
        ]);
    }

}
