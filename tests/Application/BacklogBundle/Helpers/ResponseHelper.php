<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Helpers;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;

final class ResponseHelper
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param Client $client
     * @param Crawler $crawler
     */
    public function __construct(Client $client, Crawler $crawler)
    {
        $this->client = $client;
        $this->crawler = $crawler;
        $this->response = $this->client->getResponse();
    }

    public function dump()
    {
        var_dump($this->crawler->text());
    }

    /**
     * @return int Any constant from Response::HTTP_*
     */
    public function getStatus()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @param string $selector
     *
     * @return string
     */
    public function filter($selector)
    {
        return $this->crawler->filter($selector)->text();
    }

    /**
     * @return Crawler
     */
    public function getCrawler()
    {
        return $this->crawler;
    }
}
