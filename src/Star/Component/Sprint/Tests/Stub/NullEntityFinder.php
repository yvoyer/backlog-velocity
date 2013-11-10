<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub;

use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;

/**
 * Class NullEntityFinder
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub
 */
class NullEntityFinder implements EntityFinder
{
    /**
     * Find a sprint with $name.
     *
     * @param string $name
     *
     * @return Sprint
     */
    public function findSprint($name)
    {
        throw new \RuntimeException('Method findSprint() not implemented yet.');
    }

    /**
     * Find a sprinter with $name.
     *
     * @param string $name
     *
     * @return Sprinter
     */
    public function findSprinter($name)
    {
        throw new \RuntimeException('Method findSprinter() not implemented yet.');
    }

    /**
     * Find a team with $name.
     *
     * @param string $name
     *
     * @return Team
     */
    public function findTeam($name)
    {
        throw new \RuntimeException('Method findTeam() not implemented yet.');
    }
}
 