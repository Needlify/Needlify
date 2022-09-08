<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tagName = ['tag1', 'tag2', 'tag3', 'tag4', 'tag5'];
        foreach ($tagName as $name) {
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
        }
        $manager->flush();
    }
}
