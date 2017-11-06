<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Helpers;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Link;

final class ClickOnLink implements TestRequest
{
    /**
     * @var Link
     */
    private $link;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param Link $link
     * @param array $parameters
     */
    public function __construct(Link $link, array $parameters = [])
    {
        $this->link = $link;
        $this->parameters = $parameters;
    }

    /**
     * @param Client $client
     *
     * @return Crawler
     */
    public function request(Client $client)
    {
        return $client->request($this->link->getMethod(), $this->link->getUri(), $this->parameters);
    }
}
