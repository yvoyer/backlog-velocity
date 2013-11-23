<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;

/**
 * Class ResourceCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 */
class ResourceCalculator
{
    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param \Star\Component\Sprint\Entity\Team $team
     *
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity(Team $team)
    {
        $teamMembers = $team->getMembers();

        $availableManDays = 0;
        foreach ($teamMembers as $teamMember) {
            $availableManDays += $teamMember->getAvailableManDays();
        }

        $focus = $this->calculateEstimatedFocus($team->getClosedSprints());

        return (int) floor(($availableManDays * $focus) / 100);
    }

    /**
     * Calculate the estimated focus based on past sprints.
     *
     * @param Sprint[] $sprints

     * @throws \InvalidArgumentException
     * @return int
     */
    private function calculateEstimatedFocus($sprints)
    {
        // @todo make default configurable
        // Default focus when no stats
        $estimatedFocus = 70;
        if (false === empty($sprints)) {
            $pastFocus = array();
            foreach ($sprints as $sprint) {
                if (false === $sprint instanceof Sprint) {
                    throw new \InvalidArgumentException('The calculator expects only sprints.');
                }

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
            $total = 0;
            $count = count($numbers); //total numbers in array
            foreach ($numbers as $value) {
                $total = $total + $value; // total value of array numbers
            }
            $average = ($total/$count); // get average value
        }

        return $average;
    }
}
