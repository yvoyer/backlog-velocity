<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Sprint;

use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamModel;

/**
 * Class Sprint3
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprint
 */
class Sprint3 extends SprintModel
{
    public function __construct()
    {
        parent::__construct('Sprint 3', new TeamModel('test'), 40, 50, 28);
    }

    public function getFocusFactor()
    {
        return 70;
    }
}
