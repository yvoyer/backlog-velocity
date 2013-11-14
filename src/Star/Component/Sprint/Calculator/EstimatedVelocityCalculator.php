<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;

/**
 * Class EstimatedVelocityCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 */
class EstimatedVelocityCalculator
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
        //$pastSprints = $team->getPastSprints();
        $teamMembers = $team->getMembers();

        $availableManDays = 0;
        foreach ($teamMembers as $teamMember) {
            $availableManDays += $teamMember->getAvailableManDays();
        }

        $focus = $this->calculateEstimatedFocus($team->getPastSprints());

        if (empty($focus)) {
            // Default Focus
            $focus = 70;
        }

        return (int) floor(($availableManDays * $focus) / 100);
    }

    /**
     * Calculate the estimated focus based on past sprints.
     *
     * @param SprintCollection $sprints

     * @throws \InvalidArgumentException
     * @return int
     */
    private function calculateEstimatedFocus($sprints)
    {
        $estimatedFocus = 0;

        if ($sprints instanceof SprintCollection) {
            $sprints = $sprints->all();
        }

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
