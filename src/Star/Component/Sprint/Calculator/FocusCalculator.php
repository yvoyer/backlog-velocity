<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\Sprint;

/**
 * Class FocusCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 *
 * @deprecated
 */
class FocusCalculator
{
    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * @param Sprint $sprint
     */
    public function __construct(Sprint $sprint)
    {
        $this->sprint = $sprint;
    }

    /**
     * Returns the focus calculation for the $sprint.
     *
     * @return int
     */
    public function calculate()
    {
        $manDays  = $this->sprint->getManDays();
        $velocity = $this->sprint->getActualVelocity();
        if (empty($manDays)) {
            return 0;
        }

        return (int) (($velocity / $manDays) * 100);
    }
}
