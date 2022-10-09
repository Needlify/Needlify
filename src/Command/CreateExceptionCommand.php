<?php

namespace App\Command;

use ReflectionClass;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create:exception',
    description: 'Create an exception template',
    aliases: ['a:c:e']
)]
class CreateExceptionCommand extends Command
{
    private const DEFAULT_CODE = 0;
    private const DEFAULT_STATUS = 'Internal Server Error';
    private const DEFAULT_MESSAGE = 'An error occured';

    private KernelInterface $appKernel;

    private string $templatePath;

    private Filesystem $filesystem;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
        $this->filesystem = new Filesystem();
        $this->templatePath = "{$this->appKernel->getProjectDir()}/src/FileTemplate/ExceptionTemplate.template";

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of the exception')
            ->addOption('code', 'c', InputOption::VALUE_OPTIONAL, 'Exception error code [default: 0]')
            ->addOption('status', 's', InputOption::VALUE_OPTIONAL, 'Related http status code [default: 500]')
            ->addOption('message', 'm', InputOption::VALUE_OPTIONAL, "Exception message [default: 'An error occured']");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');

        $name = $input->getArgument('name');
        $code = $input->getOption('code');
        $status = $input->getOption('status');
        $message = $input->getOption('message');

        try {
            [$name, $exceptionPath] = $this->getExceptionName($name, $helper, $io, $input, $output);
            $code = $this->getExceptionCode($code, $helper, $io, $input, $output);
            $status = $this->getExceptionStatus($status, $helper, $io, $input, $output);
            $message = $this->getExceptionMessage($message, $helper, $io, $input, $output);
        } catch (RuntimeException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $exceptionTemplate = file_get_contents($this->templatePath);
        $exceptionTemplate = str_replace(
            ['__ExceptionTemplate__', '__ExceptionErrorCode__', '__ExceptionHttpErrorCode__', '__ExceptionMessage__'],
            [$name, $code, $status, $message],
            $exceptionTemplate
        );

        $this->filesystem->copy($this->templatePath, $exceptionPath, true);
        $this->filesystem->dumpFile($exceptionPath, $exceptionTemplate);

        $io->success("src\\Exception\\{$name}.php has been created successfully!");

        return Command::SUCCESS;
    }

    private function askCustomQuestion(string $question, $helper, InputInterface $input, OutputInterface $output, mixed $default = null, array $suggestions = []): mixed
    {
        $output->writeln('');
        $output->writeln($question);

        $question = new Question('> ');
        $question->setAutocompleterValues($suggestions);
        $answer = $helper->ask($input, $output, $question);

        return $answer ?? $default;
    }

    private function getExceptionName($name, $helper, SymfonyStyle $io, InputInterface $input, OutputInterface $output): array
    {
        if (null === $name) {
            $name = $this->askCustomQuestion(
                '<info>Choose the name of your exception class (e.g. </info><comment>CustomException</comment><info>)</info>:',
                $helper,
                $input,
                $output
            );
        }

        if (!\is_string($name) || null === $name) {
            throw new RuntimeException('Invalid exception name');
        }

        $name = ucfirst($name);

        if (!\str_ends_with($name, 'Exception')) {
            $name = "{$name}Exception";
        }

        // Check if the exception already exists
        $exceptionPath = "{$this->appKernel->getProjectDir()}/src/Exception/{$name}.php";

        if ($this->filesystem->exists($exceptionPath)) {
            throw new RuntimeException("The exception 'src\\Exception\\{$name}.php' can't be generated because it already exists.");
        }

        return [$name, $exceptionPath];
    }

    private function getExceptionCode($code, $helper, SymfonyStyle $io, InputInterface $input, OutputInterface $output): string
    {
        if (null === $code) {
            $code = $this->askCustomQuestion(
                '<info>Choose the error code of your exception class (default: </info><comment>' . self::DEFAULT_CODE . '</comment><info>)</info>:',
                $helper,
                $input,
                $output,
                self::DEFAULT_CODE,
                array_values(Response::$statusTexts)
            );
        }

        if (!\is_numeric($code)) {
            throw new RuntimeException('Invalid error code');
        }

        return $code;
    }

    private function getExceptionStatus($status, $helper, SymfonyStyle $io, InputInterface $input, OutputInterface $output): string
    {
        if (null === $status) {
            $status = $this->askCustomQuestion(
                '<info>Choose the http error code of your exception class (default: </info><comment>' . self::DEFAULT_STATUS . '</comment><info>)</info>:',
                $helper,
                $input,
                $output,
                self::DEFAULT_STATUS,
                array_values(Response::$statusTexts)
            );
        }

        if (!in_array($status, Response::$statusTexts)) {
            throw new RuntimeException('Invalid status code');
        }

        $status = array_search($status, Response::$statusTexts);

        $reflectionResponse = new ReflectionClass(Response::class);

        return 'Response::' . array_search($status, $reflectionResponse->getConstants());
    }

    private function getExceptionMessage($message, $helper, SymfonyStyle $io, InputInterface $input, OutputInterface $output): string
    {
        if (null === $message) {
            $message = $this->askCustomQuestion(
                '<info>Choose the message of your exception class (default: </info><comment>' . self::DEFAULT_MESSAGE . '</comment><info>)</info>:',
                $helper,
                $input,
                $output,
                self::DEFAULT_MESSAGE
            );
        }

        return $message;
    }
}
