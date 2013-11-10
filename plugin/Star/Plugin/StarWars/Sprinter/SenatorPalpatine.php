<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\StarWars\Sprinter;

use Star\Component\Sprint\Mapping\SprinterData;

/**
 * Class SenatorPalpatine
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\StarWars\Sprinter
 */
class SenatorPalpatine extends SprinterData
{
    public function __construct()
    {
        parent::__construct('Senator Palpatine');
    }

    public function getId()
    {
        return 2;
    }
}
