<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Behat\Transliterator\Transliterator;
use Rhumsaa\Uuid\Uuid;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\Component\Identity\Identity;

final class ProjectId implements Identity
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        BacklogAssertion::string($id, 'Project id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    /**
     * @param Identity $identity
     *
     * @return bool
     */
    public function matchIdentity(Identity $identity) :bool
    {
        return $this->toString() === $identity->toString() && $identity instanceof static;
    }

    /**
     * @return string
     */
    public function toString() :string
    {
        return strval($this->id);
    }

    /**
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass() :string
    {
        return Project::class;
    }

    /**
     * @param string $string
     *
     * @return ProjectId
     */
    public static function fromString(string $string)
    {
        return new self(Transliterator::urlize($string));
    }

    /**
     * @return ProjectId
     */
    public static function uuid() :ProjectId
    {
        return new self(Uuid::uuid4()->toString());
    }
}
