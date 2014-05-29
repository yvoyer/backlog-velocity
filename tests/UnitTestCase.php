<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;

/**
 * Class UnitTestCase
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests
 */
class UnitTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Assert that the $command contains a definition of $argument.
     *
     * @param Command $command
     * @param string  $argument
     * @param mixed   $defaultValue
     * @param bool    $isRequired
     */
    protected function assertCommandHasArgument(
        Command $command,
        $argument,
        $defaultValue = null,
        $isRequired = false
    ) {
        $definition = $command->getDefinition();
        $this->assertTrue($definition->hasArgument($argument), "The argument {$argument} is not registered");
        $arg = $definition->getArgument($argument);
        $this->assertSame($defaultValue, $arg->getDefault(), 'The default value is not as expected');
        $this->assertSame($isRequired, $arg->isRequired(), 'The required flag is not as expected');
    }

    /**
     * Assert that the $command contains a definition of $option.
     *
     * @param Command $command
     * @param string  $option
     * @param mixed   $defaultValue
     */
    protected function assertCommandHasOption(Command $command, $option, $defaultValue = null)
    {
        $definition = $command->getDefinition();
        $this->assertTrue($definition->hasOption($option), "The option {$option} is not registered");
        $opt = $definition->getOption($option);
        $this->assertSame($defaultValue, $opt->getDefault(), 'The default value is not as expected');
    }

    /**
     * Assert that a $command has the basic configuration.
     *
     * @param Command $command
     * @param string  $name
     * @param string  $description
     */
    protected function assertInstanceOfCommand(
        $command,
        $name = 'unset name',
        $description = 'unset description'
    ) {
        $this->assertInstanceOf('Symfony\Component\Console\Command\Command', $command);
        $this->assertSame($name, $command->getName(), 'The name of the command is not as expected');
        $this->assertSame($description, $command->getDescription(), 'The description is not as expected');
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfCalculator($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Calculator\VelocityCalculator', $object);
    }
    /**
     * @param $object
     */
    protected function assertInstanceOfTeamFactory($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Factory\TeamFactory', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfEntityFinder($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Query\EntityFinder', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfObjectManager($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\ObjectManager', $object, false);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfPerson($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Person', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfPersonRepository($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Repository\MemberRepository', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfPlugin($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Plugin\BacklogPlugin', $object);
    }

    /**
     * Assert that $object respect the Repository contract.
     *
     * @param $object
     */
    protected function assertInstanceOfRepository($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Repository\Repository', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfRepositoryManager($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Repository\RepositoryManager', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfSprint($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Sprint', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfSprintRepository($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Repository\SprintRepository', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfSprintMember($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\SprintMember', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfTeam($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Team', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfTeamRepository($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Repository\TeamRepository', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfTeamMember($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\TeamMember', $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfWrappedRepository($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Repository\WrappedRepository', $object);
    }

// todo for testing input
//    public function testExecute()
//    {
//        // ...
//        $commandTester = new CommandTester($command);
//
//        $dialog = $command->getHelper('dialog');
//        $dialog->setInputStream($this->getInputStream('Test\n'));
//        // Equals to a user inputing "Test" and hitting ENTER
//        // If you need to enter a confirmation, "yes\n" will work
//
//        $commandTester->execute(array('command' => $command->getName()));
//
//        // $this->assertRegExp('/.../', $commandTester->getDisplay());
//    }
//
//    protected function getInputStream($input)
//    {
//        $stream = fopen('php://memory', 'r+', false);
//        fputs($stream, $input);
//        rewind($stream);
//
//        return $stream;
//    }

    /**
     * Execute a command to test.
     *
     * @param Command $command
     * @param array $input
     *
     * @return string
     */
    protected function executeCommand(Command $command, array $input = array())
    {
        $tester = new CommandTester($command);
        $tester->execute($input);

        return $tester->getDisplay();
    }

    /**
     * Returns the content of $file.
     *
     * @param string $file
     *
     * @return array
     */
    protected function getFixture($file)
    {
        return Yaml::parse($this->getFixturesFolder() . DIRECTORY_SEPARATOR . $file);
    }

    /**
     * Returns the test fixtures folder.
     *
     * @return string
     */
    protected function getFixturesFolder()
    {
        return __DIR__ . '/../Fixtures';
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockBacklogPlugin()
    {
        return $this->getMock('Star\Component\Sprint\Plugin\BacklogPlugin');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCalculator()
    {
        return $this->getMock('Star\Component\Sprint\Calculator\VelocityCalculator');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockEntity()
    {
        return $this->getMock('Star\Component\Sprint\Mapping\Entity');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeamFactory()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Factory\TeamFactory');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockEntityFinder()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Query\EntityFinder');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockPerson()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Person');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeamMember()
    {
        return $this->getMock('Star\Component\Sprint\Entity\TeamMember');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockMemberRepository()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Repository\MemberRepository');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSprint()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Sprint');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSprintMember()
    {
        return $this->getMock('Star\Component\Sprint\Entity\SprintMember');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSprintRepository()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Repository\SprintRepository');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSprintMemberRepository()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Repository\SprintMemberRepository');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSprinterRepository()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Repository\SprinterRepository');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeam()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Team');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeamRepository()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Repository\TeamRepository');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeamMemberRepository()
    {
        return $this->getMock('Star\Component\Sprint\Entity\Repository\TeamMemberRepository');
    }
}
