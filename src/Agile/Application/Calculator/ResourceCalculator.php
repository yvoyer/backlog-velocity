<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
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
     * @var int
     * todo Make configurable
     */
    private $defaultFocus = 70;

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

        $focus = $this->calculateCurrentFocus($sprint->teamId(), new \DateTimeImmutable());

        return Velocity::fromInt((int) floor(floor($availableManDays->toInt() * $focus) / 100));
    }

    /**
     * @param TeamId $teamId
     * @param \DateTimeInterface $date
     *
     * @return float
     */
    public function calculateCurrentFocus(TeamId $teamId, \DateTimeInterface $date): float
    {
        return floor(
            $this->calculateAverage(
                array_map(
                    function (FocusFactor $factor) {
                        return $factor->toInt();
                    },
                    $this->sprints->estimatedFocusOfPastSprints($teamId, $date)
                )
            )
        );
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
        BacklogAssertion::allInteger($numbers);
        $average = $this->defaultFocus;
        if (false === empty($numbers)) {
            $average = array_sum($numbers) / count($numbers);
        }

        return $average;
    }
}
