<?php

namespace Star\Component\Sprint\Model;

use Assert\Assertion;

final class EstimatedVelocity
{
    /**
     * @var int
     */
    private $value;

    private function __construct($value)
    {
        Assertion::integerish($value, 'The estimated velocity value should be an integer, %s given.');
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function toInt()
    {
        return intval($this->value);
    }

    /**
     * @param int $value
     *
     * @return EstimatedVelocity
     */
    public static function fromInt($value)
    {
        return new self($value);
    }
}
