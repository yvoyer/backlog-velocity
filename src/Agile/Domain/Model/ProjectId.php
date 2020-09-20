<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Behat\Transliterator\Transliterator;
use Ramsey\Uuid\Uuid;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\Component\Identity\Identity;

final class ProjectId implements Identity
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        BacklogAssertion::string($id, 'Project id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    public function matchIdentity(Identity $identity): bool
    {
        return $this->toString() === $identity->toString() && $identity instanceof static;
    }

    public function toString(): string
    {
        return strval($this->id);
    }

    public function entityClass(): string
    {
        return Project::class;
    }

    public static function fromString(string $string): self
    {
        return new self(Transliterator::urlize($string));
    }

    public static function uuid(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
