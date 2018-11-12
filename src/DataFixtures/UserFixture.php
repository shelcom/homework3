<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'qwerty');
        $user
            ->setRoles(['ROLE_VASYA', 'ROLE_USER'])
            ->setEmail('qwe@qwe.qwe')
            ->setPassword($encodedPassword);
        $manager->persist($user);
        $manager->flush();
    }
}