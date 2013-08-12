<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\IdentifierInterface;
use Star\Component\Sprint\Tests\Stub\Entity\StubIdentifier;

/**
 * Class Team
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 */
class Team implements EntityInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add a $sprinter to the team.
     *
     * @param SprinterInterface $sprinter
     */
    public function addSprinter($sprinter)
    {
    }

    /**
     * Returns the team name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return IdentifierInterface
     */
    public function getIdentifier()
    {
        // @todo use slugify algorithm
        return new StubIdentifier($this->name);
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'   => $this->getId(),
            'name' => $this->name,
        );
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->getIdentifier()->getKey();
    }
}
