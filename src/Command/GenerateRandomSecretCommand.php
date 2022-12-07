<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Command\Trait\CustomQuestionTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:secret:generate',
    description: 'Generate random string',
    aliases: ['a:s:g']
)]
class GenerateRandomSecretCommand extends Command
{
    use CustomQuestionTrait;

    private const DEFAULT_SECRET_LENGTH = 32;

    protected function configure(): void
    {
        $this->addOption('length', 'l', InputOption::VALUE_OPTIONAL, 'Secret length (in bytes)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $length = $input->getOption('length');

        try {
            $length = $this->getSecretLength($length, $helper, $input, $output);
        } catch (\RuntimeException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $key = bin2hex(openssl_random_pseudo_bytes($length));

        $io->info('New GLIDE_KEY was generated: ' . $key);

        return Command::SUCCESS;
    }

    private function getSecretLength($length, QuestionHelper $helper, InputInterface $input, OutputInterface $output): int
    {
        if (null === $length) {
            $length = $this->askCustomQuestion(
                '<info>Enter the number of bytes of the secret (default: </info><comment>' . self::DEFAULT_SECRET_LENGTH . '</comment><info>)</info>:',
                $helper,
                $input,
                $output,
                self::DEFAULT_SECRET_LENGTH,
            );
        }

        if (!\is_numeric($length)) {
            throw new \RuntimeException('Invalid secret length');
        }

        $length = (int) $length;

        if ($length <= 0 || $length > 2048) {
            throw new \RuntimeException('Invalid secret length');
        }

        return (int) $length;
    }
}
