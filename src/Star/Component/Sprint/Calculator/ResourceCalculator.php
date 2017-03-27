<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Exception\BacklogAssertion;
use Star\Component\Sprint\Exception\InvalidArgumentException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class ResourceCalculator implements VelocityCalculator
{
    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param int $availableManDays
     * @param SprintRepository $sprintRepository
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity($availableManDays, SprintRepository $sprintRepository)
    {
        if ($availableManDays <= 0) {
            throw new InvalidArgumentException('There should be at least 1 available man day.');
        }

        $focus = $this->calculateEstimatedFocus($sprintRepository->endedSprints());

        return (int) floor(($availableManDays * $focus) / 100);
    }

    /**
     * Calculate the estimated focus based on past sprints.
     *
     * @param Sprint[] $sprints

     * @return int
     */
    private function calculateEstimatedFocus(array $sprints)
    {
        BacklogAssertion::allIsInstanceOf($sprints, Sprint::class);
        // @todo make default configurable
        // Default focus when no stats
        $estimatedFocus = 70;
        if (0 !== count($sprints)) {
            $pastFocus = array();
            foreach ($sprints as $sprint) {
                $pastFocus[] = $sprint->getFocusFactor();
            }

            $estimatedFocus = $this->calculateAverage($pastFocus);
        }

        return (int) round($estimatedFocus);
    }

    /**
     * Returns the average calculation.
     *
     * @param array $numbers
     *
     * @return int
     */
    private function calculateAverage(array $numbers)
    {
        $average = 0;
        if (false === empty($numbers)) {
            $average = array_sum($numbers) / count($numbers);
        }

        return $average;
    }
}
