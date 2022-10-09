<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude('var')
;

// Rules here : https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/index.rst
return (new Config())
    ->setRules([
        '@Symfony' => true,
        'concat_space' => ['spacing' => 'one'],
        'ordered_imports' => ['sort_algorithm' => 'length', 'imports_order' => ['const', 'class', 'function']],
    ])
    ->setFinder($finder)
;
