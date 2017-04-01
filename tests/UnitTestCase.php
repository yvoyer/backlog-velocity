<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Entity\Factory\TeamFactory;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @deprecated todo rename to IntegrationTestCase
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
    protected function assertInstanceOfTeamFactory($object)
    {
        $this->assertInstanceOf(TeamFactory::class, $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfPerson($object)
    {
        $this->assertInstanceOf(Person::class, $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfSprint($object)
    {
        $this->assertInstanceOf(Sprint::class, $object);
    }

    /**
     * @param $object
     */
    protected function assertInstanceOfTeam($object)
    {
        $this->assertInstanceOf(Team::class, $object);
    }

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
        // todo assert return code
        $tester->execute($input);

        return $tester->getDisplay();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockBacklogPlugin()
    {
        return $this->getMockBuilder(BacklogPlugin::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockPerson()
    {
        return $this->getMockBuilder(Person::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeamMember()
    {
        return $this->getMockBuilder(TeamMember::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSprint()
    {
        return $this->getMockBuilder(Sprint::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeam()
    {
        return $this->getMockBuilder(Team::class)->getMock();
    }
}
