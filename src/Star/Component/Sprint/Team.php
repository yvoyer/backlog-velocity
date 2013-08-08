<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\IdentifierInterface;

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
        // TODO: Implement getIdentifier() method.
    }
}