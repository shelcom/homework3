<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12.12.18
 * Time: 17:03.
 */

namespace App\Repository;

use App\Entity\UserLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserLikeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserLike::class);
    }
}
