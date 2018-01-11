<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;
use Star\BacklogVelocity\Agile\Domain\Model\VelocityCalculator;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * Strategy used when the team members count, working conditions, sprint length do not change.
 * Usually this technique should be used when the team has a lot of statistics (Defined by Application).
 */
final class YesterdaysWeatherCalculator implements VelocityCalculator
{

    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param SprintId $sprintId
     *
     * @return Velocity The estimated velocity in story point
     */
    public function calculateEstimatedVelocity(SprintId $sprintId): Velocity
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Return the actual focus of the previous sprints of the given team.
     *
     * @param TeamId $teamId
     *
     * @return float
     */
    public function calculateActualFocus(TeamId $teamId): float
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
