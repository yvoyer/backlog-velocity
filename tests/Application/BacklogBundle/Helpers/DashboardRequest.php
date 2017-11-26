<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Helpers;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

final class DashboardRequest implements TestRequest
{
    /**
     * @param Client $client
     *
     * @return Crawler
     */
    public function request(Client $client)
    {
        return $client->request('GET', '/');
    }
}
