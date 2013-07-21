<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub;

use Star\Component\Sprint\Sprint;

/**
 * Class Sprint1
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub
 */
class Sprint1 extends Sprint
{
    public function __construct()
    {
    }

    public function getName()
    {
        return 'Sprint 1';
    }

    public function getEstimatedVelocity()
    {
        return 20;
    }

    public function getActualVelocity()
    {
        return 15;
    }

    public function getManDays()
    {
        return 30;
    }

    public function getFocusFactor()
    {
        return 50;
    }
}
