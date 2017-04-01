<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface SprintRepository
{
    /**
     * @param SprintId $id
     *
     * @return Sprint
     */
    public function findOneById(SprintId $id);

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint[]
     */
    public function endedSprints(ProjectId $projectId);

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint
     */
    public function activeSprintOfProject(ProjectId $projectId);

    /**
     * @param Sprint $sprint
     */
    public function saveSprint(Sprint $sprint);

    /**
     * @param Filter $filter
     *
     * @return Sprint[]
     */
    public function allSprints(Filter $filter);
}
