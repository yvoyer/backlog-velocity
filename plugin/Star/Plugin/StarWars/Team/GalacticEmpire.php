<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\StarWars\Team;

use Star\Component\Sprint\Mapping\TeamData;
use Star\Plugin\StarWars\Sprinter\DarthVader;
use Star\Plugin\StarWars\Sprinter\DS613;
use Star\Plugin\StarWars\Sprinter\LandoCalrissian;
use Star\Plugin\StarWars\Sprinter\SenatorPalpatine;
use Star\Plugin\StarWars\Sprinter\TK421;

/**
 * Class GalacticEmpire
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\StarWars\Team
 */
class GalacticEmpire extends TeamData
{
    public function __construct()
    {
        parent::__construct('The Galactic Empire');
    }

    public function getId()
    {
        return 1;
    }

    public function getMembers()
    {
        return array(
            new DarthVader(),
            new SenatorPalpatine(),
            new TK421(),
            new DS613(),
            new LandoCalrissian(),
        );
    }
}
