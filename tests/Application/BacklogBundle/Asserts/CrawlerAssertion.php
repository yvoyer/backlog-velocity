<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Asserts;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\DomCrawler\Crawler;

abstract class CrawlerAssertion extends Constraint
{
    final protected function matches($other)
    {
        Assert::assertInstanceOf(Crawler::class, $other);

        return $this->doMatches($other);
    }

    protected abstract function doMatches(Crawler $crawler);

    /**
     * @param mixed $other
     *
     * @return string
     */
    protected function failureDescription($other)
    {
        return 'Crawler contains ' . $this->toString();
    }
}
