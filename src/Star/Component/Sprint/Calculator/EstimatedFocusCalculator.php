<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\SprintInterface;

/**
 * Class EstimatedFocusCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 */
class EstimatedFocusCalculator
{
    /**
     * Calculate the estimated focus based on past sprints.
     *
     * @param SprintInterface[] $sprints

     * @throws \InvalidArgumentException
     * @return int
     */
    public function calculateEstimatedFocus(array $sprints)
    {
        $estimatedFocus = 0;
        if (false === empty($sprints)) {
            $pastFocus = array();
            foreach ($sprints as $sprint) {
                if (false === $sprint instanceof SprintInterface) {
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
