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

use App\Enum\CalloutType;

class FluidTagParsedown extends \Parsedown
{
    public function __construct()
    {
        $this->InlineTypes['{'][] = 'YoutubeEmbed';
        $this->InlineTypes['{'][] = 'ColoredText';

        $this->inlineMarkerList .= '{';
    }

    /**
     * Image tag
     * This allows to update the image tag and add the loading="lazy" attribut.
     */
    protected function inlineImage($excerpt)
    {
        $image = parent::inlineImage($excerpt);

        if (!isset($image)) {
            return null;
        }

        $image['element']['attributes']['loading'] = 'lazy';

        return $image;
    }

    /**
     * Youtube Video Embed.
     *
     * Example: {youtube:video_id}
     */
    protected function inlineYoutubeEmbed($excerpt)
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
    protected function inlineColoredText($excerpt)
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

    protected function blockQuote($Line)
    {
        // Custom callout
        if (preg_match('/^>\s?\[\!(\w+?)\](.*?)$/m', $Line['text'], $matches)) {
            $type = strtolower($matches[1]);

            if (!in_array($type, CalloutType::values())) {
                return parent::blockQuote($Line);
            }

            return [
                'element' => [
                    'name' => 'blockquote',
                    'attributes' => [
                        'class' => "$type callout",
                    ],
                    'handler' => 'lines',
                ],
            ];

        // Default blockquote
        } elseif (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            return parent::blockQuote($Line);
        }
    }
}
