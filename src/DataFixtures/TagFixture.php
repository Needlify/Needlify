<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

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
