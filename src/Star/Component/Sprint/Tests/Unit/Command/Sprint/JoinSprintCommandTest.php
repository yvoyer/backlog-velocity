<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\JoinSprintCommand;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class JoinSprintCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Sprint
 *
 * @covers Star\Component\Sprint\Command\Sprint\JoinSprintCommand
 */
class JoinSprintCommandTest extends UnitTestCase
{
    /**
     * @return JoinSprintCommand
     */
    private function getCommand()
    {
        return new JoinSprintCommand();
    }

    public function testShouldHaveSprintOption()
    {
        $this->assertCommandHasOption($this->getCommand(), 'sprint');
    }

    public function testShouldHaveSprinterOption()
    {
        $this->assertCommandHasOption($this->getCommand(), 'sprinter');
    }

    public function testShouldHaveTeamOption()
    {
        $this->assertCommandHasOption($this->getCommand(), 'team');
    }
}