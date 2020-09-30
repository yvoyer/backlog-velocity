<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Ramsey\Uuid\Uuid;
use Star\Component\Identity\Identity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class SprintId implements Identity// todo extends StringIdentity
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function matchIdentity(SprintId $sprintId): bool
    {
        return $sprintId->toString() === $this->toString();
    }

    public static function uuid(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function entityClass(): string
    {
        return Sprint::class;
    }
}
