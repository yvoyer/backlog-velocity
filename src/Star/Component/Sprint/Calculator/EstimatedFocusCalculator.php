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
        $sprints = $sprints->all();
        if (false === empty($sprints)) {
            $pastFocus = array();
            foreach ($sprints as $sprint) {
                if (false === $sprint instanceof Sprint) {
                    throw new \InvalidArgumentException('The calculator expects only sprints.');
                }

                $pastFocus[] = $sprint->getFocusFactor();
            }

            $averageCalculator = new AverageCalculator();
            $estimatedFocus = $averageCalculator->calculateAverage($pastFocus);
        }

        return (int) round($estimatedFocus);
    }
}
