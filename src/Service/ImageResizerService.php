<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use League\Glide\Urls\UrlBuilderFactory;

class ImageResizerService
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function resize(string $name, int $width, int $height, array $options = []): string
    {
        $urlBuilder = UrlBuilderFactory::create('/image/', $this->key);

        return $urlBuilder->getUrl($name, array_merge(['w' => $width, 'h' => $height], $options));
    }
}
