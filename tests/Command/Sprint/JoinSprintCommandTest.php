<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\JoinSprintCommand;
use tests\UnitTestCase;

/**
 * Class JoinSprintCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Sprint
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

    public function test_should_have_sprint_option()
    {
        $this->assertCommandHasOption($this->getCommand(), 'sprint');
    }

    public function test_should_have_sprinter_option()
    {
        $this->assertCommandHasOption($this->getCommand(), 'sprinter');
    }

    public function test_should_have_team_option()
    {
        $this->assertCommandHasOption($this->getCommand(), 'team');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_should_join_the_sprint()
    {
        $this->markTestIncomplete('todo' . __METHOD__);
    }
}
