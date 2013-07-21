<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Star\Component\Sprint\Team;

/**
 * Class TeamTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit
 */
class TeamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $name
     *
     * @return Team
     */
    private function getTeam($name = 'Team name')
    {
        return new Team($name);
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('Team name', $this->getTeam()->getName());
    }
}
