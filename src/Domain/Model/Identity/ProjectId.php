<?php

namespace Star\Component\Sprint\Domain\Model\Identity;

use Behat\Transliterator\Transliterator;
use Star\Component\Identity\Identity;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Exception\BacklogAssertion;

final class ProjectId implements Identity
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
        BacklogAssertion::string($id, 'Project id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    /**
     * @param Identity $identity
     *
     * @return bool
     */
    public function matchIdentity(Identity $identity)
    {
        return $this->toString() === $identity->toString() && $identity instanceof static;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return strval($this->id);
    }

    /**
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass()
    {
        return Project::class;
    }

    /**
     * @param string $string
     *
     * @return ProjectId
     */
    public static function fromString($string)
    {
        return new self(Transliterator::urlize($string));
    }
}
