<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InteractiveObjectFactoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Factory
 *
 * @covers Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory
 */
class InteractiveObjectFactoryTest extends UnitTestCase
{
    /**
     * @param DialogHelper    $dialog
     * @param OutputInterface $output
     *
     * @return InteractiveObjectFactory
     */
    private function getFactory(DialogHelper $dialog = null, OutputInterface $output = null)
    {
        $dialog = $this->getMockDialogHelper($dialog);
        $output = $this->getMockOutput($output);

        return new InteractiveObjectFactory($dialog, $output);
    }

    public function testShouldBeEntityCreator()
    {
        $this->assertInstanceOfEntityCreator($this->getFactory());
    }

    public function testShouldCreateTheTeamBasedOnInfoFromUser()
    {
        $name   = uniqid('name');
        $output = $this->getMockOutput();

        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->once())
            ->method('ask')
            ->with($output, '<question>Enter the team name: </question>')
            ->will($this->returnValue($name));

        $factory = $this->getFactory($dialog, $output);
        $this->assertInstanceOfTeam($factory->createTeam(''));
    }

    public function testShouldCreateTheSprintBasedOnInfoFromUser()
    {
        $name   = uniqid('name');
        $output = $this->getMockOutput();

        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->once())
            ->method('ask')
            ->with($output, '<question>Enter the sprint name: </question>')
            ->will($this->returnValue($name));

        $factory = $this->getFactory($dialog, $output);
        $this->assertInstanceOfSprint($factory->createSprint());
    }

    public function testShouldCreateTheSprintMemberBasedOnInfoFromUser()
    {
        $manDays = uniqid('manDays');
        $output  = $this->getMockOutput();

        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->at(0))
            ->method('ask')
            ->with($output, '<question>Enter the available man days for the sprint: </question>')
            ->will($this->returnValue($manDays));

        $factory = $this->getFactory($dialog, $output);
        $this->assertInstanceOfSprintMember($factory->createSprintMember());
    }

    public function testShouldCreateTheSprinterBasedOnInfoFromUser()
    {
        $name   = uniqid('name');
        $output = $this->getMockOutput();

        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->once())
            ->method('ask')
            ->with($output, "<question>Enter the sprinter's name: </question>")
            ->will($this->returnValue($name));

        $factory = $this->getFactory($dialog, $output);

        $sprinter = $factory->createSprinter('');
        $this->assertInstanceOfSprinter($sprinter);
        $this->assertSame($name, $sprinter->getName());
    }

    public function testShouldCreateTheTeamMemberBasedOnInfoFromUser()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfTeamMember($factory->createTeamMember($this->getMockSprinter(), $this->getMockTeam()));
    }

    /**
     * @param DialogHelper $dialog
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockDialogHelper(DialogHelper $dialog = null)
    {
        return $this->getMockCustom('Symfony\Component\Console\Helper\DialogHelper', $dialog, false);
    }

    /**
     * @dataProvider provideTypeData
     *
     * @param string $expectedClass
     * @param string $type
     */
    public function testShouldCreateObjectBasedOnType($expectedClass, $type)
    {
        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->any())
            ->method('ask')
            ->will($this->returnValue('anything'));

        $this->assertInstanceOf($expectedClass, $this->getFactory($dialog)->createObject($type));
    }

    public function provideTypeData()
    {
        return array(
            'Should map to sprint'        => array(SprintData::LONG_NAME, EntityCreatorInterface::TYPE_SPRINT),
            'Should map to team'          => array(TeamData::LONG_NAME, EntityCreatorInterface::TYPE_TEAM),
            'Should map to team member'   => array(TeamMemberData::LONG_NAME, EntityCreatorInterface::TYPE_TEAM_MEMBER),
            'Should map to sprinter'      => array(SprinterData::LONG_NAME, EntityCreatorInterface::TYPE_SPRINTER),
            'Should map to sprint member' => array(SprintMemberData::LONG_NAME, EntityCreatorInterface::TYPE_SPRINT_MEMBER),
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

    public function testShouldAskForTheQuestionAsLongAsTheValueIsNotValid()
    {
        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue(''));
        $dialog
            ->expects($this->at(1))
            ->method('ask')
            ->will($this->returnValue(null));
        $dialog
            ->expects($this->at(2))
            ->method('ask')
            ->will($this->returnValue('something'));

        $factory = $this->getFactory($dialog);
        $sprinter = $factory->createSprinter('');
        $this->assertSame('something', $sprinter->getName());
    }
}
