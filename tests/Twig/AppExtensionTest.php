<?php

namespace App\Tests\Repository;

use App\Entity\Topic;
use App\Twig\AppExtension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppExtensionTest extends KernelTestCase
{
    public function testIsInstanceOf()
    {
        $appExtension = new AppExtension();
        $element = new Topic();
        $element->setName('Test_topic');
        $this->assertTrue($appExtension->isInstanceof($element, 'App\\Entity\\Topic'), true);
        $this->assertTrue($appExtension->isInstanceof($element, Topic::class), true);
    }
}
