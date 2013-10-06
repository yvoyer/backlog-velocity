<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Sprint;

use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\TeamData;

/**
 * Class Sprint2
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprint
 */
class Sprint2 extends SprintData
{
    public function __construct()
    {
        parent::__construct('Sprint 2', new TeamData(''), 25, 25, 20);
    }

    public function getFocusFactor()
    {
        return 80;
    }
}
