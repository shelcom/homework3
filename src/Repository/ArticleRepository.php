<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12.12.18
 * Time: 16:15
 */

namespace App\Repository;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }
}