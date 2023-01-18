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
        $date = (new \DateTime('now', new \DateTimeZone('UTC')));
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

       $allowedKeys = ['type', 'paragraph', 'heading_1', 'heading_2', 'heading_3'];
       foreach ($content as $key => $block) {
           $content[$key] = array_intersect_key($block, array_flip($allowedKeys));
       }

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
}
