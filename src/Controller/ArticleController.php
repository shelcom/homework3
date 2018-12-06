<?php

namespace App\Controller;


use App\Entity\Article;

use App\Entity\Comment;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="article", methods={"GET"})
     */
    public function showAction(Request $request)
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findAll();
        $tag = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $articles,
        $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );
        return $this->render('blog/articles.html.twig', [
            'articles' => $articles,
            'comments' => $comments,
            'pagination' => $pagination,
            'tag' => $tag
        ]);
    }

}
