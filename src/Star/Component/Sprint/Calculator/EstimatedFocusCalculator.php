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
     * @var SprintInterface[]
     */
    private $sprints = array();

    /**
     * Add the $sprint.
     *
     * @param SprintInterface $sprint
     */
    public function addSprint(SprintInterface $sprint)
    {
        $this->sprints[] = $sprint;
    }

    /**
     * Calculate the estimated focus based on past sprints.
     *
     * @return int
     */
    public function calculateEstimatedFocus()
    {
        $estimatedFocus = 0;
        if (false === empty($this->sprints)) {
            $pastFocus      = array();
            foreach ($this->sprints as $sprint) {
                $pastFocus[] = $sprint->getFocusFactor();
            }

            $avg = new AverageCalculator($pastFocus);
            $estimatedFocus = $avg->calculate();
        }

        return (int) round($estimatedFocus);
    }
}
