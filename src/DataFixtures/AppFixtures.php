<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $prague = new Conference();
        $prague->setCity('Prague');
        $prague->setYear(2022);
        $prague->setIsInternational(false);
        $manager->persist($prague);

        $moscow = new Conference();
        $moscow->setCity('Moscow');
        $moscow->setYear(2023);
        $moscow->setIsInternational(false);
        $manager->persist($moscow);

        $comment = new Comment();
        $comment->setConference($prague);
        $comment->setAuthor('Maxim');
        $comment->setEmail('maxim@test.com');
        $comment->setText('Ty vole!');
        $manager->persist($comment);

        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $admin->setPassword(
            $this->passwordHasherFactory
                ->getPasswordHasher(Admin::class)
                ->hash('admin', null)
        );

        $manager->flush();
    }


    private function conferences()
    {
    }
}
