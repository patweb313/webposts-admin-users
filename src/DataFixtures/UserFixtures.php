<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{
    private object $hasher;
    private array $genders = ['male', 'female'];

    public function __construct(UserPasswordHasherInterface $hasher, private SluggerInterface $slugger)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 10; $i <= 70; $i++) {
            $gender = $faker->randomElement($this->genders);
            $user = new User;
            $user   ->setFirstName($faker->firstName($gender))
                    ->setLastName($faker->lastName())
                    ->setEmail($faker->email())
                    ->setImageName('0'.$i.($gender == 'male' ? 'm' : 'f').'.jpg')
                    ->setPassword($this->hasher->hashPassword($user, 'password'))
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable())
                    ->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
