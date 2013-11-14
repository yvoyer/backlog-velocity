<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\StarWars\Team;

use Star\Component\Sprint\Mapping\TeamData;
use Star\Plugin\StarWars\Sprinter\DarthVader;
use Star\Plugin\StarWars\Sprinter\SenatorPalpatine;

/**
 * Class Siths
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\StarWars\Team
 */
class Siths extends TeamData
{
    public function __construct()
    {
        parent::__construct('The Siths');
    }

    public function getId()
    {
        return 4;
    }

    public function getMembers()
    {
        return array(
            new DarthVader(),
            new SenatorPalpatine(),
        );
    }
}
