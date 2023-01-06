<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('node_modules')
    ->exclude('vendor')
;

// Rules here : https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/index.rst
return (new Config())
    ->setRules([
        '@Symfony' => true,
        'concat_space' => ['spacing' => 'one'],
        'ordered_imports' => ['sort_algorithm' => 'length', 'imports_order' => ['const', 'class', 'function']],
        'single_trait_insert_per_statement' => true,
        'header_comment' => [
            'header' => <<<EOF
This file is part of the Needlify project.
Copyright (c) Needlify <https://needlify.com/>
For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF
        ],
    ])
    ->setFinder($finder)
;
