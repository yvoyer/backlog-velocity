<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Sprinter;

use Star\Component\Sprint\Entity\Sprinter;

/**
 * Class Jabba
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprinter
 */
class Jabba extends Sprinter
{
    public function __construct()
    {
        parent::__construct('Jabba The Hutt');
    }

    public function getId()
    {
        return 8;
    }
}
