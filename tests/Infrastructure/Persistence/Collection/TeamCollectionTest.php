<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\Model\TeamModel;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamCollectionTest extends \PHPUnit_Framework_TestCase
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
        $team = TeamModel::fromString('id', 'name');
        $this->assertEmpty($this->collection->allTeams());
        $this->collection->saveTeam($team);
        $this->assertCount(1, $this->collection->allTeams());
        $this->collection->saveTeam($team);
        $this->assertCount(2, $this->collection->allTeams());
    }

    public function testShouldFindTheTeam()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $team = TeamModel::fromString('id', 'name');
        $this->collection->saveTeam($team);
        $this->assertSame($team, $this->collection->findOneByName('name'));
    }
}
