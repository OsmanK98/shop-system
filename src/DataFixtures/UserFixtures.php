<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Add user
        $user = new User();
        $user->setName("Jan");
        $user->setSurname("Kowalski");
        $user->setEmail("kowalski@gmail.com");
        $encodedPassword = $this->encoder->encodePassword($user, "12345678");
        $user->setPassword($encodedPassword);
        $user->setRoles(array('ROLE_USER'));
        $user->setPhone("412412222");
        $manager->persist($user);

        // Add administrator
        $admin = new User();
        $admin->setName("Jakub");
        $admin->setSurname("Nowak");
        $admin->setEmail("nowak@gmail.com");
        $encodedPassword = $this->encoder->encodePassword($admin, "12345678");
        $admin->setPassword($encodedPassword);
        $admin->setRoles(array('ROLE_ADMIN'));
        $admin->setPhone("124211423");
        $manager->persist($admin);
        $manager->flush();
    }
}
