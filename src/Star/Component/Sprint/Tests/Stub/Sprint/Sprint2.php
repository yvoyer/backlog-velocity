<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Sprint;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;

/**
 * Class Sprint2
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprint
 */
class Sprint2 extends Sprint
{
    public function __construct()
    {
        parent::__construct('Sprint 2', new Team(''), 25, 25, 20);
    }

    public function getFocusFactor()
    {
        return 80;
    }
}
