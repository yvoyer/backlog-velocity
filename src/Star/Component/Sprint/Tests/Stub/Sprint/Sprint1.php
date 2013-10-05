<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Sprint;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Mapping\SprintData;

/**
 * Class Sprint1
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprint
 */
class Sprint1 extends SprintData
{
    public function __construct()
    {
        parent::__construct('Sprint 1', new Team(''), 30, 20, 15);
    }

    public function getFocusFactor()
    {
        return 50;
    }
}
