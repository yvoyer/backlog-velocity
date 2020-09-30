<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Behat\Transliterator\Transliterator;
use Ramsey\Uuid\Uuid;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\Component\Identity\Identity;

final class TeamId implements Identity
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        BacklogAssertion::string($id, 'Team id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    public function toString(): string
    {
        return strval($this->id);
    }

    public function matchIdentity(TeamId $id): bool
    {
        return $this->toString() === $id->toString();
    }

    public function entityClass(): string
    {
        return Team::class;
    }

    public static function fromString(string $string): self
    {
        return new self(Transliterator::urlize($string));
    }

    public static function uuid(): self {
        return self::fromString(Uuid::uuid4()->toString());
    }
}
