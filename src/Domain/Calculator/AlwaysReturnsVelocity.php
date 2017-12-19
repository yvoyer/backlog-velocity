<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Calculator;

use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Velocity;

final class AlwaysReturnsVelocity implements VelocityCalculator
{
    /**
     * @var int
     */
    private $velocity;

    /**
     * @param int $velocity
     */
    public function __construct(int $velocity)
    {
        $this->velocity = $velocity;
    }

    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param SprintId $sprintId
     *
     * @return Velocity The estimated velocity in story point
     */
    public function calculateEstimateOfSprint(SprintId $sprintId): Velocity
    {
        return Velocity::fromInt($this->velocity);
    }
}
