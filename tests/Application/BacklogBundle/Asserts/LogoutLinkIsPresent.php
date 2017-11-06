<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Asserts;

use PHPUnit\Framework\Assert;
use Symfony\Component\DomCrawler\Crawler;

final class LogoutLinkIsPresent extends CrawlerAssertion
{
    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString() :string
    {
        return 'the logout link';
    }

    protected function doMatches(Crawler $crawler) :bool
    {
        Assert::assertContains('Logout', $crawler->text());

        // todo assert logout link (a) is there and points to a valid request
        return true;
    }
}
