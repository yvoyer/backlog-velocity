<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub;

use Star\Component\Sprint\Sprint;

/**
 * Class Sprint3
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub
 */
class Sprint3 extends Sprint
{
    public function __construct()
    {
    }

    public function getEstimatedVelocity()
    {
        return 50;
    }

    public function getActualVelocity()
    {
        return 28;
    }

    public function getManDays()
    {
        return 40;
    }

    public function getFocusFactor()
    {
        return 70;
    }
}
