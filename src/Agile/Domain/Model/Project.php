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
    public function getIdentity(): ProjectId;

    public function createTeam(TeamId $teamId, TeamName $name): Team;

    public function createSprint(
        SprintId $sprintId,
        SprintName $name,
        TeamId $teamId,
        \DateTimeInterface $createdAt
    ): Sprint;

    public function nextName(): SprintName;

    public function name(): ProjectName;
}
