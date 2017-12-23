<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;

final class Velocity
{
    /**
     * @var int
     */
    private $value;

    private function __construct($value)
    {
        BacklogAssertion::integerish($value, 'The estimated velocity value should be an integer, %s given.');
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
     * @return Velocity
     */
    public static function fromInt($value)
    {
        return new self($value);
    }
}
