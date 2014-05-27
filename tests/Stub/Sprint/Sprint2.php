<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Stub\Sprint;

use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamModel;

/**
 * Class Sprint2
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Stub\Sprint
 */
class Sprint2 extends SprintModel
{
    public function __construct()
    {
        parent::__construct('Sprint 2', new TeamModel('test'), 25, 25, 20);
    }

    public function getFocusFactor()
    {
        return 80;
    }
}
