<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixture extends Fixture implements DependentFixtureInterface
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            TagFixture::class,
            TopicFixture::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();
        $topics = $manager->getRepository(Topic::class)->findAll();

        for ($i = 0; $i < 5; ++$i) {
            $article = new Article();
            $article->setTitle($this->faker->text(120))
                ->setDescription($this->faker->text(500))
                ->setContent($this->faker->text(1000))
                ->setAuthor($this->faker->randomElement($users))
                ->setTopic($this->faker->randomElement($topics))
                ->setTags($this->faker->randomElements($tags));

            $manager->persist($article);
        }

        $manager->flush();
    }
}
