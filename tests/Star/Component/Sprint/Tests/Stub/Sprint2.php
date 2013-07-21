<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub;

use Star\Component\Sprint\Sprint;

/**
 * Class Sprint2
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub
 */
class Sprint2 extends Sprint
{
    public function __construct()
    {
    }

    public function getName()
    {
        return 'Sprint 2';
    }

    public function getEstimatedVelocity()
    {
        return 25;
    }

    public function getActualVelocity()
    {
        return 20;
    }

    public function getManDays()
    {
        return 25;
    }

    public function getFocusFactor()
    {
        return 80;
    }
}
