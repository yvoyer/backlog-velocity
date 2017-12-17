<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Repository;

use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Entity\Repository\Filter;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullSprintRepository implements SprintRepository
{
    /**
     * @param ProjectId $projectId
     * @param SprintName $name
     *
     * @return Sprint
     * @throws EntityNotFoundException
     */
    public function sprintWithName(ProjectId $projectId, SprintName $name)
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
     * @param ProjectId $projectId
     *
     * @return Sprint[]
     */
    public function endedSprints(ProjectId $projectId)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint|null
     */
    public function activeSprintOfProject(ProjectId $projectId)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param Filter $filter
     *
     * @return Sprint[]
     */
    public function allSprints(Filter $filter)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param SprintId $sprintId
     *
     * @return Sprint
     */
    public function getSprintWithIdentity(SprintId $sprintId): Sprint
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
