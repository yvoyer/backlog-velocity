<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

/**
 * Returns the estimated velocity for the sprint based on stats from previous sprints.
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface VelocityCalculator
{
    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param SprintId $sprintId
     *
     * @return Velocity The estimated velocity in story point
     */
    public function calculateEstimatedVelocity(SprintId $sprintId) :Velocity;

    /**
     * Return the actual focus of the previous sprints of the given team.
     *
     * @param TeamId $teamId
     *
     * @return float
     */
    public function calculateCurrentFocus(TeamId $teamId): float;
}
