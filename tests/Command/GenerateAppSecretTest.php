<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateAppSecretTest extends KernelTestCase
{
    private Application $application;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->application = new Application($kernel);
    }

    public function testExecutePlainCommand(): void
    {
        $command = $this->application->find('app:generate-app-secret');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
    }

    public function testExecuteAliasCommand(): void
    {
        $command = $this->application->find('app:secret');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
    }
}
