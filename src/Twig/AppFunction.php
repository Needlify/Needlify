<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Twig\TwigFunction;
use App\Service\ImageResizerService;
use Twig\Extension\AbstractExtension;

class AppFunction extends AbstractExtension
{
    private ImageResizerService $imageResizerService;

    public function __construct(ImageResizerService $imageResizerService)
    {
        $this->imageResizerService = $imageResizerService;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('image_url', [$this, 'imageUrl']),
        ];
    }

    public function imageUrl(string $name, int $width, int $height, array $options = []): string
    {
        return $this->imageResizerService->resize($name, $width, $height, $options);
    }
}
