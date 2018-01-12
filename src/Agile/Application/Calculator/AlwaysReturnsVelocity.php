<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;
use Star\BacklogVelocity\Agile\Domain\Model\VelocityCalculator;

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
    public function calculateEstimatedVelocity(SprintId $sprintId): Velocity
    {
        return Velocity::fromInt($this->velocity);
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
