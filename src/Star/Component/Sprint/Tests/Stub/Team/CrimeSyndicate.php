<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Team;

use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Tests\Stub\Sprinter\BobbaFett;
use Star\Component\Sprint\Tests\Stub\Sprinter\Jabba;

/**
 * Class CrimeSyndicate
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Team
 */
class CrimeSyndicate extends TeamData
{
    public function __construct()
    {
        parent::__construct('The Crime Syndicate');
    }

    public function getId()
    {
        return 3;
    }

    public function getMembers()
    {
        return array(
            new Jabba(),
            new BobbaFett(),
        );
    }
}
