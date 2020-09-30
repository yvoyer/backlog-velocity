<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;

final class FocusFactor
{
    /**
     * @var int
     */
    private $value;

    public function __construct(int $value)
    {
        $message = 'The focus factor must be a numeric greater than zero, %s given.';
        BacklogAssertion::greaterOrEqualThan($value, 0, $message);
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return (int) $this->value;
    }

    /**
     * @param int $value
     *
     * @return self
     */
    public static function fromInt(int $value = 0): self
    {
        return new self($value);
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        return new self((int) $value);
    }
}
