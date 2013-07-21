<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Sprint;

/**
 * Class FocusCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 */
class FocusCalculator
{
    /**
     * Returns the focus calculation for the $sprint.
     *
     * @return int
     */
    public function calculate(Sprint $sprint)
    {
        $manDays  = $sprint->getManDays();
        $velocity = $sprint->getActualVelocity();
        if (empty($manDays)) {
            return 0;
        }

        return (int) (($velocity / $manDays) * 100);
    }
}
