<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Repository\Repository;

/**
 * Class SprintRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Repository
 */
interface SprintRepository extends Repository
{
    const INTERFACE_NAME = __CLASS__;

    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function findOneByName($name);

    /**
     * @return Sprint[]
     */
    public function findNotStartedSprints();

    /**
     * @param Team $team
     *
     * @return Sprint[]
     */
    public function findNotStartedSprintsOfTeam(Team $team);
}
