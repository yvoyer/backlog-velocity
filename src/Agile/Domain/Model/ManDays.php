<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Assert\Assertion;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;

final class ManDays
{
    /**
     * @var int
     */
    private $value;

    private function __construct(int $value)
    {
        $message = 'The man days must be a numeric greater than zero, %s given.';
        BacklogAssertion::integerish($value, $message);
        BacklogAssertion::greaterOrEqualThan($value, 0, $message);
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function toInt() :int
    {
        return (int) $this->value;
    }

    /**
     * @param int $int
     *
     * @return bool
     */
    public function greaterThan(int $int) :bool
    {
        Assertion::integerish($int);
        return $this->toInt() > $int;
    }

    /**
     * @param int $int
     *
     * @return bool
     */
    public function lowerEquals(int $int) :bool
    {
        Assertion::integerish($int);
        return $this->toInt() <= $int;
    }

    /**
     * @param ManDays $days
     *
     * @return ManDays
     */
    public function addManDays(ManDays $days) :ManDays
    {
        return self::fromInt($this->toInt() + $days->toInt());
    }

    /**
     * @param int $value
     *
     * @return ManDays
     */
    public static function fromInt(int $value) :ManDays
    {
        return new self($value);
    }

    /**
     * @param string $value
     *
     * @return ManDays
     */
    public static function fromString(string $value) :ManDays
    {
        return new self((int) $value);
    }

    /**
     * @return ManDays
     */
    public static function random()
    {
        return self::fromInt(rand(1, 15));
    }
}
