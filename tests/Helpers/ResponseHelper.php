<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Helpers;

use PHPUnit\Framework\Assert;
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

    public function toHtml()
    {
        echo($this->crawler->html());
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
        $crawler = $this->crawler->filter($selector);
        Assert::assertGreaterThan(0, count($crawler), "The node '{$selector}' could not be found.");

        return $crawler->text();
    }

    /**
     * @param string $selector
     * @param string $submitText
     * @param array $data
     *
     * @return ResponseHelper
     */
    public function submitFormAt(string $selector, string $submitText, array $data) :ResponseHelper
    {
        $crawler = $this->crawler->filter($selector);
        if (count($crawler) !== 1) {
            $this->dump();
        }
        Assert::assertSame(1, count($crawler), "The form with id '{$selector}' do not exists.");

        $form = $crawler->form();
        Assert::assertContains(
            $submitText,
            $crawler->text(),
            "The form submit button  with text '{$submitText}' could not be found."
        );
        return $this->submitForm($form, $data);
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
     * @param string $selector
     * @param string $linkText
     *
     * @return ResponseHelper
     */
    public function clickLink(string $selector, string $linkText) :ResponseHelper
    {
        $crawler = $this->crawler->filter($selector)->selectLink($linkText);
        if (count($crawler) !== 1) {
            $this->dump();
        }
        Assert::assertSame(1, count($crawler), "The link '{$linkText}' cannot be found.");

        return $this->request(new ClickOnLink($crawler->link()));
    }

    /**
     * @param TestRequest $request
     *
     * @return ResponseHelper
     */
    public function request(TestRequest $request) :ResponseHelper
    {
        $crawler = $request->request($this->client);

        return new ResponseHelper($this->client, $crawler);
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
