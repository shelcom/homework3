<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\Tag;

class UserFixture extends Fixture
{
    private $passwordEncoder;


    public function load(ObjectManager $manager)
    {
        $user = new User();
        //$encodedPassword = $this->passwordEncoder->encodePassword($user, '123');
        $user
            ->setFirstName('qwe')
            ->setLastName('qwe')
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('shelcom@gmail.com')
            ->setPassword('123');
        $manager->persist($user);
        $manager->flush();
        
        for ($i = 0; $i < 4; $i++) {
            $article = new Article();
            $article->setImage('symfony4.jpg');
            $article->setTitle('This is a body of article. ');
            $article->setBody('This is a body of article. This is a body of article.
             This is a body of article.This is a body of article.
             This is a body of article.This is a body of article. 2
             ' . $i);
            $article->setAuthor($user);

            $comment = new Comment();
            $comment->setBody('This is the body of comment ' . $i . ' of article ' . $i);
            $comment->setArticle($article);
            $comment->setAuthor($user);
            $manager->persist($comment);
            $tag = new Tag();
            $tag->setName('some tag'.$i);
            $manager->persist($tag);

            $manager->persist($article);
        }
        $manager->flush();
    }
    
    
}