<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Helpers;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

interface TestRequest
{
    /**
     * @param Client $client
     *
     * @return Crawler
     */
    public function request(Client $client) :Crawler;
}
