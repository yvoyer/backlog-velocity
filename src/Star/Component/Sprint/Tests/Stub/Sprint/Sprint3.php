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
 * Class Sprint3
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprint
 */
class Sprint3 extends Sprint
{
    public function __construct()
    {
        parent::__construct('Sprint 3', new Team(''), 40, 50, 28);
    }

    public function getFocusFactor()
    {
        return 70;
    }
}
