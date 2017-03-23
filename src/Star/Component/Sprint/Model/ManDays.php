<?php

namespace Star\Component\Sprint\Model;

use Assert\Assertion;

final class ManDays
{
    /**
     * @var int
     */
    private $value;

    private function __construct($value)
    {
        Assertion::integerish($value, 'The man day value should be an integer, %s given.');
        $this->value = $value;
    }

    /**
     * @param int $value
     *
     * @return ManDays
     */
    public static function fromInt($value)
    {
        return new self($value);
    }
}
