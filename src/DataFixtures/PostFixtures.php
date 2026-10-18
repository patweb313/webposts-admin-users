<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger)
    {

    }
    public function load(ObjectManager $manager): void
    {
        // Instanciation de phpFaker
        $faker = Factory::create('fr_FR');
        // Récupère les catégories
        $categories = $manager->getRepository(Category::class)->findAll();
        // Récupère les users
        $users = $manager->getRepository(User::class)->findAll();
        // Boucle d'insertion
        for($i = 1; $i <= 42; $i++) {
            $post = new Post()
                ->setTitle($faker->words(rand(1, 3), true))
                ->setContent($faker->paragraphs(rand(3, 4), true))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', 'now')))
                ->setImage($i.'.jpg')
                ->setIsPublished($faker->boolean(90))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 days', 'now')))
                // Technique Faker
                ->setCategory($faker->randomElement($categories))
                // Technique PHP
                ->setUser($users[array_rand($users)]);
            $post->setSlug($this->slugger->slug($post->getTitle()));
            $manager->persist($post);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
       return [
           CategoryFixtures::class,
           UserFixtures::class,
       ];
    }
}

