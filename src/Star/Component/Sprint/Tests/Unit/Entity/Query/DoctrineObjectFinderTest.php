<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Query;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Entity\Query\DoctrineObjectFinder;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DoctrineObjectFinderTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Query
 *
 * @covers Star\Component\Sprint\Entity\Query\DoctrineObjectFinder
 */
class DoctrineObjectFinderTest extends UnitTestCase
{
    private function getFinder(ObjectManager $objectManager = null)
    {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);

        return new DoctrineObjectFinder($objectManager);
    }

    /**
     * @param string $entityClass
     * @param string $name
     * @param object $returnObject
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ObjectManager
     */
    private function getMockDoctrineObjectManagerExpectsGetRepository($entityClass, $name, $returnObject)
    {
        $repository = $this->getMock('\Doctrine\Common\Persistence\ObjectRepository');
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(array('name' => $name))
            ->will($this->returnValue($returnObject));

        $objectManager = $this->getMockDoctrineObjectManager();
        $objectManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($entityClass)
            ->will($this->returnValue($repository));

        return $objectManager;
    }

    public function testShouldFindTheSprintUsingTheRepository()
    {
        $name   = uniqid('name');
        $sprint = $this->getMockSprint();

        $objectManager = $this->getMockDoctrineObjectManagerExpectsGetRepository(
            SprintData::LONG_NAME,
            $name,
            $sprint
        );

        $finder = $this->getFinder($objectManager);
        $this->assertSame($sprint, $finder->findSprint($name));
    }

    public function testShouldFindTheSprinterUsingTheRepository()
    {
        $name     = uniqid('name');
        $sprinter = $this->getMockSprinter();

        $objectManager = $this->getMockDoctrineObjectManagerExpectsGetRepository(
            SprinterData::LONG_NAME,
            $name,
            $sprinter
        );

        $finder = $this->getFinder($objectManager);
        $this->assertSame($sprinter, $finder->findSprinter($name));
    }

    public function testShouldFindTheTeamUsingTheRepository()
    {
        $name = uniqid('name');
        $team = $this->getMockSprinter();

        $objectManager = $this->getMockDoctrineObjectManagerExpectsGetRepository(
            TeamData::LONG_NAME,
            $name,
            $team
        );

        $finder = $this->getFinder($objectManager);
        $this->assertSame($team, $finder->findTeam($name));
    }
}
