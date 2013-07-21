<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub;

use Star\Component\Sprint\Team;

/**
 * Class Team2
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub
 */
class Team2 extends Team
{
    public function __construct()
    {
    }

    public function getName()
    {
        return 'Team 2';
    }
}
