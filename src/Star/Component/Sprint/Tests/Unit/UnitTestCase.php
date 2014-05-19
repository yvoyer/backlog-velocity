<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Doctrine\Common\Persistence\ObjectManager as DoctrineObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Mapping\Repository\Mapping;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Repository\RepositoryManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
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
     * @todo Remove reference to mapping information
     * @param Entity $object
     */
    protected function assertInstanceOfEntity(Entity $object)
    {
        $id = 25370305258;
        $this->assertInstanceOf('Star\Component\Sprint\Mapping\Entity', $object);
        $this->assertNull($object->getId());
        $this->setAttributeValue($object, 'id', $id);
        $this->assertSame($id, $object->getId(), 'The id should be set');

        // @todo Remove toArray method
        // @todo $this->assertFalse(method_exists($object, 'toArray'), 'Method toArray should not exists.');
        $this->assertInternalType('array', $object->toArray());
    }

    /**
     * Assert that $object respect the EntityCreator contract.
     *
     * @param $object
     */
    protected function assertInstanceOfEntityCreator($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Factory\EntityCreator', $object);
    }

    /**
     * Assert that $object respect the EntityFinder contract.
     *
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
     * Assert that $object respect the Sprint contract.
     *
     * @param $object
     */
    protected function assertInstanceOfSprint($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Sprint', $object);
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
     * Assert that $object respect the Sprint contract.
     *
     * @param $object
     */
    protected function assertInstanceOfSprinter($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Sprinter', $object);
    }

    /**
     * Assert that $object respect the Team contract.
     *
     * @param $object
     */
    protected function assertInstanceOfTeam($object)
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Team', $object);
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
     * @return BacklogApplication|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockBacklogApplication()
    {
        return $this->getMockCustom('Star\Component\Sprint\BacklogApplication', null, false);
    }

    /**
     * @return BacklogPlugin|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockBacklogPlugin()
    {
        return $this->getMockCustom('Star\Component\Sprint\Plugin\BacklogPlugin');
    }

    /**
     * @param DoctrineObjectManager $objectManager
     *
     * @return DoctrineObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockDoctrineObjectManager(DoctrineObjectManager $objectManager = null)
    {
        return $this->getMockCustom('Doctrine\Common\Persistence\ObjectManager', $objectManager, false);
    }

    /**
     * @param ObjectRepository $repository
     *
     * @return ObjectRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockDoctrineRepository(ObjectRepository $repository = null)
    {
        return $this->getMockCustom('Doctrine\Common\Persistence\ObjectRepository', $repository, false);
    }

    /**
     * @param Entity $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Entity
     */
    protected function getMockEntity(Entity $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Mapping\Entity', $object, false);
    }

    /**
     * @param \Star\Component\Sprint\Entity\Factory\EntityCreator $creator
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityCreator
     */
    protected function getMockEntityCreator(EntityCreator $creator = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Factory\EntityCreator', $creator);
    }

    /**
     * @param EntityFinder $finder
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityFinder
     */
    protected function getMockEntityFinder(EntityFinder $finder = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Query\EntityFinder', $finder);
    }

    /**
     * @param Mapping $mapping
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Mapping
     */
    protected function getMockClassMapping(Mapping $mapping = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Mapping\Repository\Mapping', $mapping, false);
    }

    /**
     * @param OutputInterface $output
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockOutput(OutputInterface $output = null)
    {
        // @todo use custom interface
        return $this->getMockCustom('Symfony\Component\Console\Output\OutputInterface', $output);
    }

    /**
     * @return Person|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockPerson()
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Person');
    }

    /**
     * @param RepositoryManager $repositoryManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockRepositoryManager(RepositoryManager $repositoryManager = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Repository\RepositoryManager', $repositoryManager);
    }

    /**
     * @param TeamMember $teamMember
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeamMember(TeamMember $teamMember = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\TeamMember', $teamMember, false);
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
     * @param ObjectManager $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ObjectManager
     */
    protected function getMockObjectManager(ObjectManager $object = null)
    {
        return $this->getMockCustom(ObjectManager::CLASS_NAME, $object, false);
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
        return $this->getMockCustom('Star\Component\Sprint\Entity\Sprint', $object);
    }

    /**
     * @param SprintCollection $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Sprint
     */
    protected function getMockSprintCollection(SprintCollection $object = null)
    {error_log(__METHOD__);
        return $this->getMockCustom(SprintCollection::CLASS_NAME, $object, false);
    }

    /**
     * @param Sprinter $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Sprint
     */
    protected function getMockSprinter(Sprinter $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Sprinter', $object, false);
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
     * @param TeamMemberRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|TeamMemberRepository
     */
    protected function getMockTeamMemberRepository(TeamMemberRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\TeamMemberRepository', $object, false);
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
