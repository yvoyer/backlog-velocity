<?php

namespace Star\Component\Sprint\Model;

use Assert\Assertion;
use Star\Component\Sprint\Exception\BacklogAssertion;

final class ManDays
{
    /**
     * @var int
     */
    private $value;

    private function __construct($value)
    {
        $message = 'The man days must be a numeric greater than zero, %s given.';
        BacklogAssertion::integerish($value, $message);
        BacklogAssertion::greaterOrEqualThan($value, 0, $message);
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function toInt()
    {
        return (int) $this->value;
    }

    /**
     * @param int $int
     *
     * @return bool
     */
    public function greaterThan($int)
    {
        Assertion::integerish($int);
        return $this->toInt() > $int;
    }

    /**
     * @param ManDays $days
     *
     * @return ManDays
     */
    public function addManDays(ManDays $days)
    {
        return self::fromInt($this->toInt() + $days->toInt());
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
