<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Collection\SprintCollection;
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
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param integer $availableManDays The available man days for the sprint
     * @param SprintCollection $sprints
     *
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity($availableManDays, SprintCollection $sprints)
    {
        $estimatedFocus = new EstimatedFocusCalculator();
        $focus          = $estimatedFocus->calculateEstimatedFocus($sprints);

        if (empty($focus)) {
            // Default Focus
            $focus = 70;
        }

        return (int) floor(($availableManDays * $focus) / 100);
    }
}
