<?php

namespace App\Controller\Api;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends Controller
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("api/article/{text}")
     */
    public function showArticle(Article $text)
    {
        return $this->json(['article' => $text]);
    }

    /**
     * @Route("api/article", methods={"POST"})
     */
    public function createArticle(Request $request)
    {
        $json = $request->getContent();

        $article = $this->serializer->deserialize($json, Article::class, JsonEncoder::FORMAT);
        // TODO validate article

        return $this->json(['article' => $article]);
    }

//
//    /**
//     * @Route("/articles", name="article", methods={"GET"})
//     */
//    public function showAction(Request $request)
//    {
//        $articles = $this->getDoctrine()
//            ->getRepository(Article::class)
//            ->findAll();
//        $comments = $this->getDoctrine()
//            ->getRepository(Comment::class)
//            ->findAll();
//        $tag = $this->getDoctrine()
//            ->getRepository(Tag::class)
//            ->findAll();
//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $articles,
//            $request->query->getInt('page', 1)/*page number*/,
//            2/*limit per page*/
//        );
//
//        return $this->render('blog/articles.html.twig', [
//            'articles' => $articles,
//            'comments' => $comments,
//            'pagination' => $pagination,
//            'tag' => $tag,
//        ]);
//    }
}
