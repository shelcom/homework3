<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\UserLike;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommentRepository;
use App\Services\LikeServices;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class ArticleController extends Controller
{
    /**
     * @Route("/", name="article", methods={"GET"})
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

    /**
     * @Route("/article/{id}", name="articles")
     */
 public function showArticle(Request $request, Article $article, CommentRepository $commentRepository)
    {

        $comments = new Comment();
        $comments->setAuthor($this->getUser());


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
        $em = $this->getDoctrine()->getManager();
        $allLike = $em->getRepository(UserLike::class)
            ->allLike($article);

        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'allLike' => $allLike,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post", name="")
     */
    public function article(Request $request)
    {   $user = $this->getUser();
        $article = new Article();
        $article->setAuthor($this->getUser());
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $article->setUser($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $img = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$img->guessExtension();
            $img->move($this->getParameter('image_directory'), $fileName);
            $data->setImage($fileName);

            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article');

        }

        return $this->render('blog/post.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/article/{id}/like", name="like")
     */
    public function LikeAction(Article $article)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $like = $em->getRepository(UserLike::class)
            ->findOneBy(['user' => $user, 'article' => $article]);

        if (!$like) {
            $like = new UserLike();
            $like
                ->setArticle($article)
                ->setUser($user)
                ->setLikes(true);
            $em->persist($like);
            $em->flush();

        } else {
            $em->remove($like);
            $em->flush();
        }

        return $this->redirectToRoute('article', ['id' => $article->getId()]);

    }
}
