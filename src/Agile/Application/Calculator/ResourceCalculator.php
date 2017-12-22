<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;
use Star\BacklogVelocity\Agile\Domain\Model\VelocityCalculator;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class ResourceCalculator implements VelocityCalculator
{
    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @param SprintRepository $sprints
     */
    public function __construct(SprintRepository $sprints)
    {
        $this->sprints = $sprints;
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
        $sprint = $this->sprints->getSprintWithIdentity($sprintId);
        $availableManDays = $sprint->getManDays();
        if ($availableManDays->lowerEquals(0)) {
            throw new InvalidArgumentException('There should be at least 1 available man day.');
        }


        $focus = $this->calculateCurrentFocus($sprintId);

        return Velocity::fromInt((int) floor(($availableManDays->toInt() * $focus)));
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
        // todo filter sprints based on project and team
        $sprint = $this->sprints->getSprintWithIdentity($sprintId);
        $closedSprints = $this->sprints->endedSprints($sprint->projectId());
        BacklogAssertion::allIsInstanceOf($closedSprints, Sprint::class);

        // @todo make default configurable
        // Default focus when no stats
        $estimatedFocus = 70;
        if (0 !== count($closedSprints)) {
            $pastFocus = array_map(
                function (Sprint $sprint) {
                    return $sprint->getFocusFactor();
                },
                $closedSprints
            );

            $estimatedFocus = $this->calculateAverage($pastFocus);
        }

        return (int) round($estimatedFocus) / 100;
    }

    /**
     * Returns the average calculation.
     *
     * @param array $numbers
     *
     * @return float
     */
    private function calculateAverage(array $numbers) :float
    {
        $average = 0;
        if (false === empty($numbers)) {
            $average = array_sum($numbers) / count($numbers);
        }

        return $average;
    }
}
