<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\StarWars\Sprinter;

use Star\Component\Sprint\Mapping\SprinterData;

/**
 * Class HanSolo
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\StarWars\Sprinter
 */
class HanSolo extends SprinterData
{
    public function __construct()
    {
        parent::__construct('Han Solo');
    }

    public function getId()
    {
        return 6;
    }
}
