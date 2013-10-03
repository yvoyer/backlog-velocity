<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Team;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Tests\Stub\Sprinter\DarthVader;
use Star\Component\Sprint\Tests\Stub\Sprinter\DS613;
use Star\Component\Sprint\Tests\Stub\Sprinter\LandoCalrissian;
use Star\Component\Sprint\Tests\Stub\Sprinter\SenatorPalpatine;
use Star\Component\Sprint\Tests\Stub\Sprinter\TK421;

/**
 * Class GalacticEmpire
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Team
 */
class GalacticEmpire extends Team
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
