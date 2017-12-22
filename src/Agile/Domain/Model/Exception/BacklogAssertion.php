<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Exception;

use Assert\Assertion;

final class BacklogAssertion extends Assertion
{
    /**
     * Exception to throw when an assertion failed.
     *
     * @var string
     */
    protected static $exceptionClass = InvalidAssertionException::class;
}
