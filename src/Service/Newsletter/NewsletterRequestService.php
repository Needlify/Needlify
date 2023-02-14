<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Newsletter;

use App\Model\Newsletter\NewsletterPage;
use App\Model\Newsletter\NewsletterContent;
use Symfony\Component\HttpClient\HttpClient;

class NewsletterRequestService
{
    public function getTodaysNewsletterInfos(): NewsletterPage
    {
        // $date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $date = (new \DateTimeImmutable('now'));
        $client = HttpClient::create();
        $request = $client->request('POST', "https://api.notion.com/v1/databases/{$_ENV['NOTION_NEWSLETTER_DATABASE_ID']}/query", [
           'headers' => [
              'Authorization' => "Bearer {$_ENV['NOTION_API_SECRET']}",
              'Notion-Version' => '2021-08-16',
              'Content-Type' => 'application/json',
           ],
           'json' => [
              'filter' => [
                 'and' => [
                    [
                       'property' => 'Date',
                       'date' => [
                          'equals' => $date->format('Y-m-d'),
                       ],
                    ],
                 ],
              ],
           ],
        ]);

        $pageInfoArray = $request->toArray();

        $pageInfo = new NewsletterPage();
        $pageInfo->setCanBePublished(!empty($pageInfoArray['results']));

        if ($pageInfo->getCanBePublished()) {
            $currentPage = $pageInfoArray['results'][0];
            $pageInfo
                ->setTitle($currentPage['properties']['Content']['title'][0]['plain_text'])
                ->setEmoji($currentPage['icon']['emoji'])
                ->setPageId($currentPage['id'])
                ->setDate($date)
                ->setNewsletterUrl($currentPage['url']);
        }

        return $pageInfo;
    }

    public function getTodaysNewsletterContent(NewsletterPage $pageInfo): NewsletterContent
    {
        $client = HttpClient::create();
        $request = $client->request('GET', "https://api.notion.com/v1/blocks/{$pageInfo->getPageId()}/children", [
            'headers' => [
                'Authorization' => "Bearer {$_ENV['NOTION_API_SECRET']}",
                'Notion-Version' => '2021-08-16',
                'Content-Type' => 'application/json',
            ],
        ]);

        $pageContentArray = $request->toArray();

        $content = $pageContentArray['results'];

        $allowedKeys = ['type', 'paragraph', 'heading_1', 'heading_2', 'heading_3', 'text', 'content', 'link', 'url', 'annotations', 'bold', 'italic', 'strikethrough', 'underline'];

        $content = $this->array_filter_recursive($content, $allowedKeys);
        $content = $this->remove_empty_block($content);

        $pageContent = new NewsletterContent();
        $pageContent
            ->setNewsletterPage($pageInfo)
            ->setContent($content);

        return $pageContent;
    }

    public function updateNotionPageStatus(NewsletterPage $newsletterInfos)
    {
        $pageId = $newsletterInfos->getPageId();

        $client = HttpClient::create();
        $request = $client->request('PATCH', "https://api.notion.com/v1/pages/{$pageId}", [
            'headers' => [
                'Authorization' => "Bearer {$_ENV['NOTION_API_SECRET']}",
                'Notion-Version' => '2021-08-16',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'properties' => [
                    'Status' => [
                    'select' => [
                        'name' => 'Published',
                    ],
                    ],
                ],
            ],
        ]);
    }

    private function array_filter_recursive($array, array $allowedKeys)
    {
        // On filtre les clés au niveau n
        $array = array_filter($array, function ($key) use ($allowedKeys) {
            return in_array($key, $allowedKeys) || is_int($key);
        }, ARRAY_FILTER_USE_KEY);

        // Pour chaque clé restantes
        foreach ($array as $key => $value) {
            // On check si la valeur est une liste
            // Si oui on appel array_filter_recursive eton assigne la valeur à $array[$key]
            if (is_array($value)) {
                $array[$key] = $this->array_filter_recursive($value, $allowedKeys);
            }
            // Si non, on passe
        }

        return $array;
    }

    private function remove_empty_block($content)
    {
        foreach ($content as $key => $block) {
            $type = $block['type'];
            if ([] === $block[$type]['text']) {
                unset($content[$key]);
            }
        }

        return $content;
    }
}
