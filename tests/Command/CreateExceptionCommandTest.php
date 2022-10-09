<?php

namespace App\Tests\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateExceptionCommandTest extends KernelTestCase
{
    private Application $application;
    private Filesystem $filesystem;
    private KernelInterface $appKernel;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->appKernel = $kernel;

        $this->application = new Application($this->appKernel);
        $this->application->setAutoExit(false);

        $this->filesystem = new Filesystem();
    }

    private function testCommand(string $commandName)
    {
        $command = $this->application->find($commandName);
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['__Test__', '1', 'Not Found', 'HelloWorld']);

        $commandTester->execute(['command' => $command->getName()]);

        $commandTester->assertCommandIsSuccessful();

        $this->filesystem->remove("{$this->appKernel->getProjectDir()}/src/Exception/__Test__Exception.php");
    }

    public function testExecutePlainCommand(): void
    {
        $this->testCommand('app:create:exception');
    }

    public function testExecuteAliasCommand(): void
    {
        $this->testCommand('a:c:e');
    }

    public function testCommandInvalidNameFailure()
    {
        $command = $this->application->find('app:create:exception');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['']);

        $commandTester->execute(['command' => $command->getName()]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }

    public function testCommandInvalidCodeFailure()
    {
        $command = $this->application->find('app:create:exception');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['__Test__', 'test']);

        $commandTester->execute(['command' => $command->getName()]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }

    public function testCommandInvalidStatusFailure()
    {
        $command = $this->application->find('app:create:exception');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['__Test__', '1', 'Test']);

        $commandTester->execute(['command' => $command->getName()]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }

    public function testCommandDuplicateFailure()
    {
        $command = $this->application->find('app:create:exception');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['__Test__', '1', 'Not Found', 'HelloWorld']);

        $commandTester->execute(['command' => $command->getName()]);

        $commandTester->assertCommandIsSuccessful();

        $commandTester->execute(['command' => $command->getName()]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());

        $this->filesystem->remove("{$this->appKernel->getProjectDir()}/src/Exception/__Test__Exception.php");
    }
}
