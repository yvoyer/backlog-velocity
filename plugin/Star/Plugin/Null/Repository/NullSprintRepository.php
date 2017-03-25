<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Repository;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\ProjectId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullSprintRepository implements SprintRepository
{
    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function findOneByName($name)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param Sprint $sprint
     */
    public function saveSprint(Sprint $sprint)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * todo add @param ProjectId $projectId
     *
     * @return Sprint[]
     */
    public function endedSprints()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * todo add ProjectId arg
     *
     * @return Sprint[]
     */
    public function activeSprints()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
