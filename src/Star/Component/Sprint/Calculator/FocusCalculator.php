<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

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
     * @param int $manDays
     * @param int $velocity
     *
     * @return int
     */
    public function calculate($manDays, $velocity)
    {
        if (empty($manDays)) {
            return 0;
        }

        return (int) (($velocity / $manDays) * 100);
    }
}
