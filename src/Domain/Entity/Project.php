<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Entity;

use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\TeamName;
use Star\Component\Sprint\Domain\Visitor\ProjectNode;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * Contract for project classes.
 */
interface Project extends ProjectNode
{
    /**
     * @return ProjectId
     */
    public function getIdentity();

    /**
     * @param TeamId $teamId
     * @param TeamName $name
     *
     * @return Team
     */
    public function createTeam(TeamId $teamId, TeamName $name) :Team;

    /**
     * @param SprintId $sprintId
     * @param SprintName $name
     * @param TeamId $teamId
     * @param \DateTimeInterface $createdAt
     *
     * @return Sprint
     */
    public function createSprint(
        SprintId $sprintId,
        SprintName $name,
        TeamId $teamId,
        \DateTimeInterface $createdAt
    ) :Sprint;

    /**
     * @return SprintName
     */
    public function nextName();

    /**
     * @return ProjectName
     */
    public function name() :ProjectName;
}
