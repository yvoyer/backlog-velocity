<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit;

use Star\Plugin\Doctrine\DoctrineObjectFinder;
use Star\Plugin\Doctrine\DoctrineObjectManagerAdapter;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DoctrineObjectFinderTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit
 *
 * @covers Star\Plugin\Doctrine\DoctrineObjectFinder
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
     * @param DoctrineObjectManagerAdapter $adapter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineObjectManagerAdapter
     */
    protected function getMockObjectManagerAdapter(DoctrineObjectManagerAdapter $adapter = null)
    {
        return $this->getMockCustom('Star\Plugin\Doctrine\DoctrineObjectManagerAdapter', $adapter, false);
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
