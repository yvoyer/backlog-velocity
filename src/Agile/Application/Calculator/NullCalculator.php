<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;
use Star\BacklogVelocity\Agile\Domain\Model\VelocityCalculator;

final class NullCalculator implements VelocityCalculator
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
        return Velocity::fromInt(0);
    }

    /**
     * Return the actual focus of the previous sprints of the given sprint.
     *
     * @param SprintId $sprintId
     *
     * @return float
     */
    public function calculateCurrentFocus(SprintId $sprintId): float
    {
        return 0;
    }
}
