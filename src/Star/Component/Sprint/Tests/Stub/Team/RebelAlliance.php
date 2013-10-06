<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Team;

use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Tests\Stub\Sprinter\HanSolo;
use Star\Component\Sprint\Tests\Stub\Sprinter\LandoCalrissian;
use Star\Component\Sprint\Tests\Stub\Sprinter\LeiaSkywalker;
use Star\Component\Sprint\Tests\Stub\Sprinter\LukeSkywalker;

/**
 * Class RebelAlliance
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Team
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
