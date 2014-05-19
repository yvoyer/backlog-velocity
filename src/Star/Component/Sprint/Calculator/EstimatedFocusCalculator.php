<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Sprint;

/**
 * Class EstimatedFocusCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 * @deprecated
 */
class EstimatedFocusCalculator
{
    /**
     * Calculate the estimated focus based on past sprints.
     *
     * @param SprintCollection $sprints

     * @throws \InvalidArgumentException
     * @return int
     */
    public function calculateEstimatedFocus(SprintCollection $sprints)
    {
        $estimatedFocus = 0;
        if (count($sprints) > 0) {
            $pastFocus = array();
            foreach ($sprints as $sprint) {
                $pastFocus[] = $sprint->getFocusFactor();
            }

            $estimatedFocus = $this->getAverage($pastFocus);
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
    private function getAverage(array $numbers)
    {
        if (false === empty($numbers)) {
            return array_sum($numbers) / count($numbers);
        }

        return 0;
    }
}
