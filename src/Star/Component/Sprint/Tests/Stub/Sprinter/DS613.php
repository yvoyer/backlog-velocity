<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Sprinter;

use Star\Component\Sprint\Entity\Sprinter;

/**
 * Class DS613
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprinter
 */
class DS613 extends Sprinter
{
    public function __construct()
    {
        parent::__construct('Pilot DS-61-3');
    }

    public function getId()
    {
        return 4;
    }
}
