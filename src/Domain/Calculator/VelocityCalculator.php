<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Calculator;

use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Velocity;

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
    public function calculateEstimateOfSprint(SprintId $sprintId) :Velocity;
}
