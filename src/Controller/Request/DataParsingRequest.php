<?php

namespace App\Controller\Request;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
class DataParsingRequest
{
    const PATH_FOR_PARSING = 'http://allkoncert.ru/test.html';

    public function __construct(
        private HttpClientInterface $client
    )
    {
    }

    public function getAllSources()
    {
        $result = $this->client->request(
            'GET',
            self::PATH_FOR_PARSING
        );

        if ($result->getStatusCode() !== 200) {
            throw new \Exception(
                sprintf('Server return result with code %d', $result->getStatusCode()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $crawler = new Crawler($result->getContent());
        $textAreasValues = $crawler->filter('textarea')->each(function ($node, $i) {
            return $node->html();
        });

        if (!$textAreasValues) {
            throw new \Exception('No content search in source', Response::HTTP_BAD_REQUEST);
        }

        unset($textAreasValues[0]);

        return $textAreasValues;
    }
}