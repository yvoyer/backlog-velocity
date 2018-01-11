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
     * Return the actual focus of the team based on previous sprints.
     *
     * @param TeamId $teamId
     *
     * @return float todo FocusFactor instead
     */
    public function calculateActualFocus(TeamId $teamId): float;

// todo     public function calculateSprintFocus(): FocusFactor;
}
