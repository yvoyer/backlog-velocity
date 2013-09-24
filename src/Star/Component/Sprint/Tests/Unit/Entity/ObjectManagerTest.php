<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\EntityFinderInterface;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class ObjectManagerTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity
 */
class ObjectManagerTest extends UnitTestCase
{
    /**
     * @param \Star\Component\Sprint\Entity\Factory\EntityCreatorInterface $creator
     * @param \Star\Component\Sprint\Entity\Query\EntityFinderInterface    $finder
     *
     * @return ObjectManager
     */
    private function getManager(
        EntityCreatorInterface $creator = null,
        EntityFinderInterface $finder = null
    ) {
        $creator = $this->getMockEntityCreator($creator);
        $finder = $this->getMockEntityFinder($finder);

        return new ObjectManager($creator, $finder);
    }

    public function testShouldFindSprintInRepository()
    {
        $sprintName = 'some sprint name';
        $sprint     = $this->getMockSprint();

        $finder = $this->getMockEntityFinder();
        $finder
            ->expects($this->once())
            ->method('findSprint')
            ->with($sprintName)
            ->will($this->returnValue($sprint));

        $manager = $this->getManager(null, $finder);
        $this->assertSame($sprint, $manager->getSprint($sprintName));
    }

    public function testShouldReturnASprintWhenNotInFinder()
    {
        $sprintName = 'some sprint name';
        $sprint     = $this->getMockSprint();
//        $sprint
//            ->expects($this->once())
//            ->method('setName')
//            ->with($sprintName);

        $creator = $this->getMockEntityCreator();
        $creator
            ->expects($this->once())
            ->method('createSprint')
            ->will($this->returnValue($sprint));

        $manager = $this->getManager($creator);
        $this->assertSame($sprint, $manager->getSprint($sprintName));
    }
}
