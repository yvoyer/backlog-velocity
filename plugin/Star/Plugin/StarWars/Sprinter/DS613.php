<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\StarWars\Sprinter;

use Star\Component\Sprint\Mapping\SprinterData;

/**
 * Class DS613
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\StarWars\Sprinter
 */
class DS613 extends SprinterData
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
