<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Query;

use Star\Component\Sprint\Entity\SprinterInterface;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\TeamInterface;

/**
 * Class EntityFinderInterface
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Query
 */
interface EntityFinderInterface
{
    /**
     * Find a sprint with $name.
     *
     * @param string $name
     *
     * @return Sprint
     */
    public function findSprint($name);

    /**
     * Find a sprinter with $name.
     *
     * @param string $name
     *
     * @return SprinterInterface
     */
    public function findSprinter($name);

    /**
     * Find a team with $name.
     *
     * @param string $name
     *
     * @return TeamInterface
     */
    public function findTeam($name);
}
