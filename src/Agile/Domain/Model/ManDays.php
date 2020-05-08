<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

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
        BacklogAssertion::greaterOrEqualThan($value, 0, $message);
        $this->value = $value;
    }

    public function toInt(): int
    {
        return (int) $this->value;
    }

    public function greaterThan(int $int): bool
    {
        return $this->toInt() > $int;
    }

    public function lowerEquals(int $int): bool
    {
        return $this->toInt() <= $int;
    }

    public function addManDays(ManDays $days): self
    {
        return self::fromInt($this->toInt() + $days->toInt());
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public static function fromString(string $value): self
    {
        return new self((int) $value);
    }

    public static function random(): self
    {
        return self::fromInt(rand(1, 15));
    }
}
