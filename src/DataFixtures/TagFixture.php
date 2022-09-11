<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = ['tag1', 'tag2', 'tag3', 'tag4', 'tag5'];
        foreach ($tags as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);

            $manager->persist($tag);
        }
        $manager->flush();
    }
}
