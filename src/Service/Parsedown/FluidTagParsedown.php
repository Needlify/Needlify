<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Parsedown;

class FluidTagParsedown extends \Parsedown
{
    public function __construct()
    {
        $this->InlineTypes['{'][] = 'YoutubeEmbed';
        $this->inlineMarkerList .= '{';
    }

    /**
     * Youtube Video Embed.
     */
    protected function inlineYoutubeEmbed($excerpt)
    {
        if (preg_match('/^{youtube:(.{11})}/', $excerpt['text'], $matches)) {
            return [
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'div',
                    'rawHtml' => "<iframe src='https://www.youtube.com/embed/{$matches[1]}' loading='lazy' frameborder='0' allowfullscreen allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture'></iframe>",
                    'allowRawHtmlInSafeMode' => true,
                    'attributes' => [
                        'class' => 'iframe-container',
                    ],
                ],
            ];
        }
    }
}
