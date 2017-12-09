<?php

namespace Star\Component\Sprint\Domain\Model\Identity;

use Behat\Transliterator\Transliterator;
use Rhumsaa\Uuid\Uuid;
use Star\Component\Identity\Identity;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Exception\BacklogAssertion;

final class TeamId implements Identity
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        BacklogAssertion::string($id, 'Team id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return strval($this->id);
    }

    public function matchIdentity(TeamId $id) :bool
    {
        return $this->toString() === $id->toString();
    }

    /**
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass()
    {
        return Team::class;
    }

    /**
     * @param string $string
     *
     * @return TeamId
     */
    public static function fromString($string)
    {
        return new self(Transliterator::urlize($string));
    }

    /**
     * @return TeamId
     */
    public static function uuid() :self {
        return self::fromString(Uuid::uuid4()->toString());
    }
}
