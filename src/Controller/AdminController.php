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
use App\Entity\User;
use App\Entity\UserLike;
use App\Form\AdminType;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use App\Services\LikeServices;
use App\Repository\UserLikeRepository;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @Route("/admin/dashboard" , name="admin/dashboard")
     */
    public function dashboard()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("admin/edituser/{id}" , name="user.edit")
     *
     * @paramConverter("user", class="App\Entity\User")
     * @param User $user
     * @return \Symfony\Component\Form\Extension\HttpFoundation\Response
     */
    public function editUserAction(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {   $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin/dashboard');

        }
        return $this->render('admin/editUser.html.twig', [
            'users' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/article/{id}", name="admin/articles")
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
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $img = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$img->guessExtension();
            $img->move($this->getParameter('image_directory'), $fileName);
            $data->setImage($fileName);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('admin', ['id' => $article->getId()]);
        }
        return $this->render('admin/edit.html.twig', [
            'article' => $article,
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