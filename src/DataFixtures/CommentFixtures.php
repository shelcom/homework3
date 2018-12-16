<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 04.12.18
 * Time: 17:11.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 4; ++$i) {
            $article = new Article();
            $article->setText('This is a body of article '.$i);

            $comment = new Comment();
            $comment->setBody('This is the body of comment '.$i.' of article '.$i);
            $comment->setArticle($article);
            $manager->persist($comment);
            $tag = new Tag();
            $tag->setName('some tag'.$i);
            $manager->persist($tag);

            $manager->persist($article);
        }
        $manager->flush();
    }
}
