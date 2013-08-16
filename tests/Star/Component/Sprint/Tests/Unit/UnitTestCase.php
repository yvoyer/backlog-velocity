<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\IdentifierInterface;
use Star\Component\Sprint\Entity\Member;
use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Repository\Repository;
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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockEntity(EntityInterface $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\EntityInterface', $object, false);
    }

    /**
     * @param IdentifierInterface $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockIdentifier(IdentifierInterface $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\IdentifierInterface', $object, false);
    }

    /**
     * @param Member $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockMember(Member $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Member', $object, false);
    }

    /**
     * @param MemberRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockMemberRepository(MemberRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\MemberRepository', $object, false);
    }

    /**
     * @param Repository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockRepository(Repository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Repository\Repository', $object);
    }

    /**
     * @param Sprint $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSprint(Sprint $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Sprint', $object, false);
    }

    /**
     * @param SprintRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSprintRepository(SprintRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\SprintRepository', $object, false);
    }

    /**
     * @param Team $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeam(Team $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Team', $object, false);
    }

    /**
     * @param TeamRepository $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeamRepository(TeamRepository $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Repository\TeamRepository', $object, false);
    }
}
