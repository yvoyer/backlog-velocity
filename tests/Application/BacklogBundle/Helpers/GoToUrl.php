<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Helpers;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

final class GoToUrl implements TestRequest
{
    /**
     * @var string
     */
    private $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @param Client $client
     *
     * @return Crawler
     */
    public function request(Client $client) :Crawler
    {
        return $client->request('GET', $this->url);
    }
}
