<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\DefaultObjectFactory;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
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
        $this->assertInstanceOfTeam($factory->createTeam(''));
    }

    public function testShouldCreateSprint()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfSprint($factory->createSprint(''));
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
        $factory = $this->getFactory();
        $sprintMember = $factory->createSprintMember();
        $this->assertInstanceOfSprintMember($sprintMember);

        $this->assertSame(0, $sprintMember->getAvailableManDays());
        $this->assertSame(0, $sprintMember->getActualVelocity());
        $this->assertInstanceOfSprint($sprintMember->getSprint());
        $this->assertInstanceOfTeamMember($sprintMember->getTeamMember());
    }

    public function testShouldCreateTeamMember()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfTeamMember($factory->createTeamMember($this->getMockSprinter(), $this->getMockTeam()));
    }
}
