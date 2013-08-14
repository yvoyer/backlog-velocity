<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\SprintInterface;

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
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param integer $availableManDays The available man days for the sprint
     *
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity($availableManDays)
    {
        $aPastFocus = array();
        foreach ($this->sprints as $sprint) {
            $aPastFocus[] = $sprint->getFocusFactor();
        }

        $averageCalculator = new AverageCalculator($aPastFocus);
        $focus = $averageCalculator->calculate();

        if (empty($focus)) {
            // Default Focus
            $focus = 70;
        }

        return (int) floor(($availableManDays * $focus) / 100);
    }
}
