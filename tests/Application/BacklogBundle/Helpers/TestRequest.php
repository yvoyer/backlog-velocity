<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Helpers;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

interface TestRequest
{
    /**
     * @param Client $client
     *
     * @return Crawler
     */
    public function request(Client $client);
}
