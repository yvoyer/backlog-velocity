<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamModel;
use tests\UnitTestCase;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InteractiveObjectFactoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Entity\Factory
 * @deprecated todo Remove in favor of better encapsulation
 * @covers Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory
 */
class InteractiveObjectFactoryTest extends UnitTestCase
{
    public function setUp()
    {
        $this->markTestIncomplete('review');
    }

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

    /**
     * @dataProvider provideQuestionForCreatorMethodData
     *
     * @param $method
     * @param $question
     * @param $name
     * @param $classType
     */
    public function testShouldNeverAskForTheQuestionWhenNameAlreadyValid($method, $question, $name, $classType)
    {
        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->never())
            ->method('ask');
        $factory = $this->getFactory($dialog);

        $object = $factory->{$method}($name);
        $this->assertInstanceOf($classType, $object);
        $this->assertSame($name, $object->getName());
    }

    /**
     * @dataProvider provideQuestionForCreatorMethodData
     *
     * @param $method
     * @param $question
     * @param $name
     * @param $classType
     */
    public function testShouldAskForTheQuestionAsLongAsTheValueIsNotValid($method, $question, $name, $classType)
    {
        $output = $this->getMockOutput();

        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->at(0))
            ->method('ask')
            ->with($output, '<question>' . $question . '</question>')
            ->will($this->returnValue(''));
        $dialog
            ->expects($this->at(1))
            ->method('ask')
            ->with($output, '<question>' . $question . '</question>')
            ->will($this->returnValue(null));
        $dialog
            ->expects($this->at(2))
            ->method('ask')
            ->with($output, '<question>' . $question . '</question>')
            ->will($this->returnValue($name));

        $factory = $this->getFactory($dialog);
        $sprinter = $factory->{$method}('');
        $this->assertSame($name, $sprinter->getName());
    }

    public function provideQuestionForCreatorMethodData()
    {
        return array(
            'Should create the sprint based on user info' => array(
                'createSprint', "Enter the sprint name: ", 'sprint name', SprintModel::CLASS_NAME,
            ),
            'Should create the team based on user info' => array(
                'createTeam', "Enter the team name: ", 'team name', TeamModel::CLASS_NAME,
            ),
            'Should create the sprinter based on user info' => array(
                'createSprinter', "Enter the sprinter's name: ", 'sprinter name', PersonModel::CLASS_NAME,
            ),
        );
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
        $sprintMember = $factory->createSprintMember(0, 0, $this->getMockSprint(), $this->getMockTeamMember());
        $this->assertInstanceOfSprintMember($sprintMember);
    }

    public function testShouldCreateTheTeamMemberBasedOnInfoFromUser()
    {
        $teamMember = $this->getFactory()->createTeamMember($this->getMockSprinter(), $this->getMockTeam(), 12);

        $this->assertInstanceOfTeamMember($teamMember);
        $this->assertSame(12, $teamMember->getAvailableManDays());
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
}
