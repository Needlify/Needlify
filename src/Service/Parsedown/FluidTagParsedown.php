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
        $this->InlineTypes['{'][] = 'ColoredText';

        $this->inlineMarkerList .= '{';
    }

    /**
     * Youtube Video Embed.
     *
     * Example: {youtube:video_id}
     */
    protected function inlineYoutubeEmbed($excerpt): array
    {
        if (preg_match('/^\{youtube\:(.{11})\}/', $excerpt['text'], $matches)) {
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

    /**
     * Colored text.
     *
     * Example: {c:color}text{/c}
     * color can be a css color like red or blue or a hexadecimal color like #ebeb00
     */
    protected function inlineColoredText($excerpt): array
    {
        if (preg_match('/^{c:([#\w]\w+)}(.*?){\/c}/', $excerpt['text'], $matches)) {
            return [
                // How many characters to advance the Parsedown's
                // cursor after being done processing this tag.
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'span',
                    'text' => $matches[2],
                    'handler' => 'line',
                    'attributes' => [
                        'style' => 'color: ' . $matches[1],
                    ],
                ],
            ];
        }
    }
}
