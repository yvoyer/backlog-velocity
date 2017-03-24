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
     * @return int
     */
    public function toInt()
    {
        return (int) $this->value;
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
