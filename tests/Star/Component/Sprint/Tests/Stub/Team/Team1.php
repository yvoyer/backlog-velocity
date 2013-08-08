<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Team;

use Star\Component\Sprint\Team;

/**
 * Class Team1
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Team
 */
class Team1 extends Team
{
    public function __construct()
    {
    }

    public function getName()
    {
        return 'Team 1';
    }
}
