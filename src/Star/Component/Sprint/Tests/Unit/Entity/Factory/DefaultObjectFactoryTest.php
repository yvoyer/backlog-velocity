<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\DefaultObjectFactory;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DefaultObjectFactoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Factory
 *
 * @covers Star\Component\Sprint\Entity\Factory\DefaultObjectFactory
 */
class DefaultObjectFactoryTest extends UnitTestCase
{
    /**
     * @return DefaultObjectFactory
     */
    private function getFactory()
    {
        return new DefaultObjectFactory();
    }

    public function testShouldBeOfTypeEntityCreator()
    {
        $this->assertInstanceOfEntityCreator($this->getFactory());
    }

    public function testShouldCreateTeam()
    {
        $factory = $this->getFactory();
        $team = $factory->createTeam('some-name');

        $this->assertInstanceOfTeam($team);
        $this->assertSame('some-name', $team->getName());
    }

    public function testShouldCreateSprint()
    {
        $factory = $this->getFactory();
        $sprint = $factory->createSprint('some-name');

        $this->assertInstanceOfSprint($sprint);
        $this->assertSame('some-name', $sprint->getName());
    }

    public function testShouldCreateSprinter()
    {
        $name    = uniqid('name');
        $factory = $this->getFactory();

        $sprinter = $factory->createSprinter($name);
        $this->assertInstanceOfSprinter($sprinter);
        $this->assertSame($name, $sprinter->getName());
    }

    public function testShouldCreateSprinterMember()
    {
        $sprint     = $this->getMockSprint();
        $teamMember = $this->getMockTeamMember();

        $factory      = $this->getFactory();
        $sprintMember = $factory->createSprintMember(0, 0, $sprint, $teamMember);
        $this->assertInstanceOfSprintMember($sprintMember);

        $this->assertSame(0, $sprintMember->getAvailableManDays());
        $this->assertSame(0, $sprintMember->getActualVelocity());
        $this->assertSame($sprint, $sprintMember->getSprint());
        $this->assertSame($teamMember, $sprintMember->getTeamMember());
    }

    public function testShouldCreateTeamMember()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfTeamMember($factory->createTeamMember($this->getMockSprinter(), $this->getMockTeam()));
    }
}
