<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Sprinter;

use Star\Component\Sprint\Entity\Sprinter;

/**
 * Class BobbaFett
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprinter
 */
class BobbaFett extends Sprinter
{
    public function __construct()
    {
        parent::__construct('Bobba Fett');
    }

    public function getId()
    {
        return 9;
    }
}
