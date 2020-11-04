<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('test@test.fr');
        $user->setIsEnabled(true);
        $user->setLogin('User');
        $user->setPrenom('Jhon');
        $user->setNom('Doe');
        $manager->persist($user);

        $user->setPassword($this->passwordEncoder->encodePassword($user, 'doe'));

        $manager->flush();
    }
}
