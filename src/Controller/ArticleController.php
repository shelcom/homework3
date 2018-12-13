<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommentRepository;
use App\Form\CommentType;

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
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $articles,
        $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );

        return $this->render('blog/articles.html.twig', [
            'articles' => $articles,
            'comments' => $comments,
            'pagination' => $pagination,
            'tag' => $tag,
        ]);
    }

    /**
     * @Route("/article/{id}", name="articles")
     */
    public function showArticle(Request $request, Article $article, CommentRepository $commentRepository)
    {
        $comments = new Comment();

        $article->addComment($comments);
        $form = $this->createForm(CommentType::class, $comments);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comments);
            $em->flush();

            return $this->redirectToRoute('article', ['id' => $article->getId()]);
        }

        $comments = $commentRepository->findBy(['article' => $article]);

        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}
