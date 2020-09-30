<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Behat\Transliterator\Transliterator;
use Ramsey\Uuid\Uuid;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\Component\Identity\Identity;

// todo move to other BoundContext
final class PersonId implements Identity
{
    /**
     * @var string
     */
    private $id;

    private function __construct(string $id)
    {
        BacklogAssertion::string($id, 'Person id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    public function matchIdentity(PersonId $id): bool
    {
        return $this->toString() === $id->toString();
    }

    public function toString(): string
    {
        return strval($this->id);
    }

    public function entityClass(): string
    {
        return Person::class;
    }

    public static function fromString(string $string): self
    {
        return new self(Transliterator::urlize($string));
    }

    public static function uuid(): self
    {
        return self::fromString(Uuid::uuid4()->toString());
    }
}
