<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\ProjectId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface SprintRepository
{
    /**
     * @param string $name todo Change to Id
     *
     * @return Sprint
     */
    public function findOneByName($name);

    /**
     * todo add @param ProjectId $projectId
     *
     * @return Sprint[]
     */
    public function endedSprints();

    /**
     * todo add ProjectId arg
     *
     * @return Sprint[]
     */
    public function activeSprints();

    /**
     * @param Sprint $sprint
     */
    public function saveSprint(Sprint $sprint);
}
