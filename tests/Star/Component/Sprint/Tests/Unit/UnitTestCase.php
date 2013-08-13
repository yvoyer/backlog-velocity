<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\IdentifierInterface;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Repository\Repository;

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
     * @param Team $object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTeam(Team $object = null)
    {
        return $this->getMockCustom('Star\Component\Sprint\Entity\Team', $object, false);
    }
}
