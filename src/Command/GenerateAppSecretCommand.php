<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:key:generate',
    description: 'Generate App secret for the application',
    aliases: ['a:k:g']
)]
class GenerateAppSecretCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $a = '0123456789abcdefghijklmnopqrstuvwxyz';
        $secret = '';
        for ($i = 0; $i < 32; ++$i) {
            $secret .= $a[rand(0, 15)];
        }

        $io->info('New APP_SECRET was generated: ' . $secret);

        return Command::SUCCESS;
    }
}
