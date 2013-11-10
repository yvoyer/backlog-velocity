<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\StarWars\Team;

use Star\Component\Sprint\Mapping\TeamData;
use Star\Plugin\StarWars\Sprinter\HanSolo;
use Star\Plugin\StarWars\Sprinter\LandoCalrissian;
use Star\Plugin\StarWars\Sprinter\LeiaSkywalker;
use Star\Plugin\StarWars\Sprinter\LukeSkywalker;

/**
 * Class RebelAlliance
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\StarWars\Team
 */
class RebelAlliance extends TeamData
{
    public function __construct()
    {
        parent::__construct('The Rebel Alliance');
    }

    public function getId()
    {
        return 2;
    }

    public function getMembers()
    {
        return array(
            new LukeSkywalker(),
            new HanSolo(),
            new LeiaSkywalker(),
            new LandoCalrissian(),
        );
    }
}
