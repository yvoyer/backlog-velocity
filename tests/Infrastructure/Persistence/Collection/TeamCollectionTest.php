<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\UnitTestCase;

/**
 * Class TeamCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Collection\TeamCollection
 */
class TeamCollectionTest extends UnitTestCase
{
    /**
     * @var TeamCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new TeamCollection();
    }

    public function testShouldManageTeam()
    {
        $this->assertEmpty($this->collection->allTeams());
        $this->collection->saveTeam($this->getMockTeam());
        $this->assertCount(1, $this->collection->allTeams());
        $this->collection->saveTeam($this->getMockTeam());
        $this->assertCount(2, $this->collection->allTeams());
    }

    public function testShouldFindTheTeam()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $team = $this->getMockTeam();
        $team
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->saveTeam($team);
        $this->assertInstanceOfTeam($this->collection->findOneByName('name'));
    }
}
