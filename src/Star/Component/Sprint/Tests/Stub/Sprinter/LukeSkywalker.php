<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Sprinter;

use Star\Component\Sprint\Mapping\SprinterData;

/**
 * Class LukeSkywalker
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Sprinter
 */
class LukeSkywalker extends SprinterData
{
    public function __construct()
    {
        parent::__construct('Luke Skywalker');
    }

    public function getId()
    {
        return 5;
    }
}
