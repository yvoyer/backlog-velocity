<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Helpers;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DomCrawler\Form;
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

        if ($this->getStatus() === Response::HTTP_INTERNAL_SERVER_ERROR) {
            //echo $crawler->text();
            throw new \RuntimeException('Reponse triggered exception: ' . $crawler->filter('title')->text());
        }
    }

    public function dump()
    {
        echo($this->crawler->text());
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
     * @param Form $form
     * @param array $data
     *
     * @return ResponseHelper
     */
    public function submitForm(Form $form, array $data) :ResponseHelper
    {
        $crawler = $this->client->submit($form, $data);

        return new self($this->client, $crawler);
    }

    /**
     * @return bool
     */
    public function isRedirect() :bool
    {
        return $this->client->getResponse()->isRedirect();
    }

    /**
     * @return ResponseHelper
     */
    public function followRedirect() :ResponseHelper
    {
        return new self($this->client, $this->client->followRedirect());
    }

    /**
     * @return string
     */
    public function getCurrentUrl() :string
    {
        return str_replace('http://localhost', '', $this->crawler->getUri());
    }

    /**
     * @return Crawler
     */
    public function getCrawler()
    {
        return $this->crawler;
    }

    /**
     * @param $serviceId
     *
     * @return object
     */
    public function getService($serviceId)
    {
        return $this->client->getContainer()->get($serviceId);
    }
}
