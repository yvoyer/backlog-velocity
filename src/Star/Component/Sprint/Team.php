<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

/**
 * Class Team
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 */
class Team
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
}