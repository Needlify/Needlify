<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenerateRandomSecretCommandTest extends KernelTestCase
{
    private Application $application;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->application = new Application($kernel);
    }

    public function testExecutePlainCommand(): void
    {
        $command = $this->application->find('app:secret:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--length' => 32,
        ]);

        $commandTester->assertCommandIsSuccessful();
    }

    public function testExecuteAliasCommand(): void
    {
        $command = $this->application->find('a:s:g');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--length' => 32,
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
