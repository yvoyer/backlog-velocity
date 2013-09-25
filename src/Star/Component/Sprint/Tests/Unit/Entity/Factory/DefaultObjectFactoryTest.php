<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\DefaultObjectFactory;
use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
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
        $this->assertInstanceOfSprint($factory->createSprint());
    }

    public function testShouldCreateMember()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfMember($factory->createMember());
    }

    public function testShouldCreateSprinter()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfSprinter($factory->createSprinter());
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
        $this->assertInstanceOfTeamMember($factory->createTeamMember());
    }

    /**
     * @dataProvider provideTypeData
     *
     * @param string $expectedClass
     * @param string $type
     */
    public function testShouldCreateObjectBasedOnType($expectedClass, $type)
    {
        $this->assertInstanceOf($expectedClass, $this->getFactory()->createObject($type));
    }

    public function provideTypeData()
    {
        return array(
            'Should map to sprint'        => array(Sprint::LONG_NAME,       EntityCreatorInterface::TYPE_SPRINT),
            'Should map to team'          => array(Team::LONG_NAME,         EntityCreatorInterface::TYPE_TEAM),
            'Should map to team member'   => array(TeamMember::LONG_NAME,   EntityCreatorInterface::TYPE_TEAM_MEMBER),
            'Should map to sprinter'      => array(Sprinter::LONG_NAME,     EntityCreatorInterface::TYPE_SPRINTER),
            'Should map to sprint member' => array(SprintMember::LONG_NAME, EntityCreatorInterface::TYPE_SPRINT_MEMBER),
        );
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The type 'unsupported-type' is not supported.
     */
    public function testShouldThrowExceptionWhenTypeNotSupported()
    {
        $this->getFactory()->createObject('unsupported-type');
    }
}
