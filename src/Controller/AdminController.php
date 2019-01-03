<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 21.12.18
 * Time: 13:24
 */

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use App\Services\LikeServices;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin" , name="admin")
     */
    public function admin(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

        }

        return $this->render('security/admin.html.twig', [
            'article' => $article,
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("admin/article/{id}", name="admin/articles")
     */
    public function showArticle(Request $request, Article $article, LikeServices $likes)
    {
        $allLike = $likes->countLikes($article);
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

        //$comments = $commentRepository->findBy(['article' => $article]);

        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'allLike' => $allLike,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/delete/article/{id}", name="delete/articles")
     * @return Response
     */
    public function delete(Article $article){
        $manager = $this->getDoctrine()->getManager();

        foreach ($article->getComments() as $comment) {
            $manager->remove($comment);
        }

        $manager->remove($article);
        $manager->flush();

        $this->addFlash('deleteArticle', 'L\'article a bien étais supprimer');

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("admin/edit/article/{id}", name="edit/articles", methods="GET|POST")
     */
    public function edit(Request $request, Article $article)
    {
        $user = $this->getUser();
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
            return $this->redirectToRoute('admin');

        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("admin/create/article/{id}", name="create/articles", methods="GET|POST")
     */
    public function create(Request $request)
    {
        $user = $this->getUser();
        $article = new Article();
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
            return $this->redirectToRoute('admin');

        }

        return $this->render('blog/post.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}