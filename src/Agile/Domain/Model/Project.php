<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * Contract for project classes.
 */
interface Project
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
