<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Query;

use Star\Component\Sprint\Entity\Query\DoctrineObjectFinder;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter;
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
    /**
     * @param DoctrineObjectManagerAdapter $adapter
     *
     * @return DoctrineObjectFinder
     */
    private function getFinder(DoctrineObjectManagerAdapter $adapter = null)
    {
        $adapter = $this->getMockObjectManagerAdapter($adapter);

        return new DoctrineObjectFinder($adapter);
    }

    /**
     * @param string $adapterMethod
     * @param string $name
     * @param mixed  $returnObject
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineObjectManagerAdapter
     */
    private function getMockObjectManagerAdapterExpectsRepository($adapterMethod, $name, $returnObject)
    {
        $repositoryName = str_ireplace('get', '', $adapterMethod);

        $repository = $this->getMockCustom('Star\Component\Sprint\Entity\Repository\\' . $repositoryName, null, false);
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(array('name' => $name))
            ->will($this->returnValue($returnObject));

        $adapter = $this->getMockObjectManagerAdapter();
        $adapter
            ->expects($this->once())
            ->method($adapterMethod)
            ->will($this->returnValue($repository));

        return $adapter;
    }

    public function testShouldFindTheSprintUsingTheRepository()
    {
        $name   = uniqid('name');
        $sprint = $this->getMockSprint();

        $adapter = $this->getMockObjectManagerAdapterExpectsRepository(
            'getSprintRepository',
            $name,
            $sprint
        );

        $finder = $this->getFinder($adapter);
        $this->assertSame($sprint, $finder->findSprint($name));
    }

    public function testShouldFindTheSprinterUsingTheRepository()
    {
        $name     = uniqid('name');
        $sprinter = $this->getMockSprinter();

        $adapter = $this->getMockObjectManagerAdapterExpectsRepository(
            'getSprinterRepository',
            $name,
            $sprinter
        );

        $finder = $this->getFinder($adapter);
        $this->assertSame($sprinter, $finder->findSprinter($name));
    }

    public function testShouldFindTheTeamUsingTheRepository()
    {
        $name = uniqid('name');
        $team = $this->getMockSprinter();

        $adapter = $this->getMockObjectManagerAdapterExpectsRepository(
            'getTeamRepository',
            $name,
            $team
        );

        $finder = $this->getFinder($adapter);
        $this->assertSame($team, $finder->findTeam($name));
    }
}
