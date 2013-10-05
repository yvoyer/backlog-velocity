<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\Member;
use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprinterInterface;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Entity\Query\EntityFinderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;

/**
 * Class UnitTestCase
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit
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
     * @param EntityInterface $object
     */
    protected function assertInstanceOfEntity($object)
    {
        $id = 25370305258;
        $this->assertInstanceOf('Star\Component\Sprint\Entity\EntityInterface', $object);
        $this->assertNull($object->getId());
        $this->setAttributeValue($object, 'id', $id);
        $this->assertSame($id, $object->getId(), 'The id should be set');

        // @todo Remove toArray method
        // @todo $this->assertFalse(method_exists($object, 'toArray'), 'Method toArray should not exists.');
        $this->assertInternalType('array', $object->toArray());
    }

    /**
     * Assert that $object respect the EntityCreatorInterface contract.
     *
     * @param $object
     */
    protected function assertInstanceOfEntityCreator($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Factory\EntityCreatorInterface', $object);
    }

    /**
     * Assert that $object respect the EntityFinderInterface contract.
     *
     * @param $object
     */
    protected function assertInstanceOfEntityFinder($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Query\EntityFinderInterface', $object);
    }

    /**
     * Assert that $object respect the MemberInterface contract.
     *
     * @param $object
     */
    protected function assertInstanceOfMember($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\MemberInterface', $object);
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
     * Assert that $object respect the SprintInterface contract.
     *
     * @param $object
     */
    protected function assertInstanceOfSprint($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\SprintInterface', $object);
    }

    /**
     * Assert that $object respect the SprintMember contract.
     *
     * @param $object
     */
    protected function assertInstanceOfSprintMember($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\SprintMember', $object);
    }

    /**
     * Assert that $object respect the SprintInterface contract.
     *
     * @param $object
     */
    protected function assertInstanceOfSprinter($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\SprinterInterface', $object);
    }

    /**
     * Assert that $object respect the TeamInterface contract.
     *
     * @param $object
     */
    protected function assertInstanceOfTeam($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\TeamInterface', $object);
    }

    /**
     * Assert that $object respect the TeamMember contract.
     *
     * @param $object
     */
    protected function assertInstanceOfTeamMember($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\TeamMember', $object);
    }

    /**
     * Assert that $object respect the WrappedRepository contract.
     *
     * @param $object
     */
    protected function assertInstanceOfWrappedRepository($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Repository\WrappedRepository', $object);
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
     * Returns a mock object for the specified class.
     *
     * @param  string  $originalClassName
     * @param  object  $mockObject
     * @param  boolean $callOriginalConstructor
     * @param  array   $methods
     * @param  array   $arguments
     * @param  string  $mockClassName
     * @param  boolean $callOriginalClone
     * @param  boolean $callAutoload
     * @param  boolean $cloneArguments
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCustom(
        $originalClassName,
        $mockObject = null,
        $callOriginalConstructor = true,
        $methods = array(),
        array $arguments = array(),
        $mockClassName = '',
        $callOriginalClone = true,
        $callAutoload = true,
        $cloneArguments = false
    ) {
        if (null === $mockObject) {
            $mockObject = $this->getMock(
                $originalClassName,
                $methods,
                $arguments,
                $mockClassName,
                $callOriginalConstructor,
                $callOriginalClone,
                $callAutoload,
                $cloneArguments
            );
        }

        return $mockObject;
    }

    /**
     * @param EntityInterface $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityInterface
     */
    protected function getMockEntity(EntityInterface $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\EntityInterface', $object, false);
    }

    /**
     * @param \Star\Component\Sprint\Entity\Factory\EntityCreatorInterface $creator
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityCreatorInterface
     */
    protected function getMockEntityCreator(EntityCreatorInterface $creator = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Factory\EntityCreatorInterface', $creator);
    }

    /**
     * @param EntityFinderInterface $finder
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityFinderInterface
     */
    protected function getMockEntityFinder(EntityFinderInterface $finder = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Query\EntityFinderInterface', $finder);
    }

    /**
     * @param InteractiveObjectFactory $factory
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|InteractiveObjectFactory
     */
    protected function getMockInteractiveObjectFactory(InteractiveObjectFactory $factory = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory', $factory, false);
    }

    /**
     * @param TeamMember $teamMember
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeamMember(TeamMember $teamMember = null)
    {
        return $this->getMockCustom(TeamMember::LONG_NAME, $teamMember, false);
    }

    /**
     * @param Member $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Member
     * @deprecated
     */
    protected function getMockMember(Member $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Member', $object, false);
    }

    /**
     * @param MemberRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|MemberRepository
     */
    protected function getMockMemberRepository(MemberRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\MemberRepository', $object, false);
    }

    /**
     * @param Repository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Repository
     */
    protected function getMockRepository(Repository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Repository\Repository', $object);
    }

    /**
     * @param Sprint $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Sprint
     */
    protected function getMockSprint(Sprint $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Sprint', $object, false);
    }

    /**
     * @param SprintCollection $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Sprint
     */
    protected function getMockSprintCollection(SprintCollection $object = null)
    {
        return $this->getMockCustom(SprintCollection::CLASS_NAME, $object, false);
    }

    /**
     * @param SprinterInterface $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Sprint
     */
    protected function getMockSprinter(SprinterInterface $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\SprinterInterface', $object, false);
    }

    /**
     * @param SprintRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SprintRepository
     */
    protected function getMockSprintRepository(SprintRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\SprintRepository', $object, false);
    }

    /**
     * @param SprintMemberRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SprintRepository
     */
    protected function getMockSprintMemberRepository(SprintMemberRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\SprintMemberRepository', $object, false);
    }

    /**
     * @param SprinterRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SprinterRepository
     */
    protected function getMockSprinterRepository(SprinterRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\SprinterRepository', $object, false);
    }

    /**
     * @param Team $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Team
     */
    protected function getMockTeam(Team $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Team', $object, false);
    }

    /**
     * @param TeamRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|TeamRepository
     */
    protected function getMockTeamRepository(TeamRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\TeamRepository', $object, false);
    }

    /**
     * Set the $property on $object to $value.
     *
     * @param object $object
     * @param string $property
     * @param mixed  $value
     */
    protected function setAttributeValue($object, $property, $value)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
