<?php

namespace Akyos\ShopBundle\DataFixtures;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserShopFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new BaseUserShop();
        $user
            ->setEmail("shop@akyos.fr")
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'root'
            ))
            ->setRoles(["ROLE_SUPER_ADMIN"])
        ;

        $manager->persist($user);
        $manager->flush();
    }
}
