<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface SprintRepository
{
    const SERVICE_ID = 'backlog.repositories.sprints';

    /**
     * @param ProjectId $projectId
     * @param SprintName $name
     *
     * @return Sprint
     * @throws EntityNotFoundException
     */
    public function sprintWithName(ProjectId $projectId, SprintName $name);

    /**
     * @param TeamId $teamId
     * @param \DateTimeInterface $before
     *
     * @return FocusFactor[]
     */
    public function estimatedFocusOfPastSprints(TeamId $teamId, \DateTimeInterface $before): array;

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint|null
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

    /**
     * @param SprintId $sprintId
     *
     * @return Sprint
     */
    public function getSprintWithIdentity(SprintId $sprintId) :Sprint;
}
