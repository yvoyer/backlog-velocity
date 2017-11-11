<?php

namespace Star\Component\Sprint\Domain\Exception;

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
