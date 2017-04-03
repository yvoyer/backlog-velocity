<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class FocusCalculator
{
    /**
     * Returns the focus calculation for the $sprint.
     *
     * @param int $manDays todo Mandays
     * @param int $velocity todo Velocity
     *
     * @return int todo FocusFactor
     */
    public function calculate($manDays, $velocity)
    {
        if (empty($manDays)) {
            return 0;
        }

        return (int) (($velocity / $manDays) * 100);
    }
}