<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Trait\Command;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

trait CustomQuestionTrait
{
    private function askCustomQuestion(string $question, QuestionHelper $helper, InputInterface $input, OutputInterface $output, mixed $default = null, array $suggestions = []): mixed
    {
        $output->writeln('');
        $output->writeln($question);

        $question = new Question('> ');
        $question->setAutocompleterValues($suggestions);
        $answer = $helper->ask($input, $output, $question);

        return $answer ?? $default;
    }
}
