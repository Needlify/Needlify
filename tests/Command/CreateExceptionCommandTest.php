<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

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

        $result = $commandTester->execute(['command' => $command->getName()]);

        $this->assertEquals(Command::FAILURE, $result);
    }

    public function testCommandInvalidCodeFailure()
    {
        $command = $this->application->find('app:create:exception');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['__Test__', 'test']);

        $result = $commandTester->execute(['command' => $command->getName()]);

        $this->assertEquals(Command::FAILURE, $result);
    }

    public function testCommandInvalidStatusFailure()
    {
        $command = $this->application->find('app:create:exception');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['__Test__', '1', 'Test']);

        $result = $commandTester->execute(['command' => $command->getName()]);

        $this->assertEquals(Command::FAILURE, $result);
    }

    public function testCommandDuplicateFailure()
    {
        $command = $this->application->find('app:create:exception');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['__Test__', '1', 'Not Found', 'HelloWorld']);

        $commandTester->execute(['command' => $command->getName()]);

        $commandTester->assertCommandIsSuccessful();

        $result = $commandTester->execute(['command' => $command->getName()]);

        $this->assertEquals(Command::FAILURE, $result);

        $this->filesystem->remove("{$this->appKernel->getProjectDir()}/src/Exception/__Test__Exception.php");
    }
}
