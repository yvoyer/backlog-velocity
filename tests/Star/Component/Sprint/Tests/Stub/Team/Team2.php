<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Team;

use Star\Component\Sprint\Entity\Team;

/**
 * Class Team2
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Team
 */
class Team2 extends Team
{
    public function __construct()
    {
        parent::__construct('Team 2');
    }
}
