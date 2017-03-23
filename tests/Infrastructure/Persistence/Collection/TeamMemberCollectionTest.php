<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Sprint\Collection\TeamMemberCollection;
use tests\UnitTestCase;

/**
 * Class TeamMemberCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Collection\TeamMemberCollection
 */
class TeamMemberCollectionTest extends UnitTestCase
{
    /**
     * @var TeamMemberCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new TeamMemberCollection();
    }

    public function test_should_be_countable()
    {
        $this->assertInstanceOf('\Countable', $this->collection);
    }

    /**
     * @depends test_should_be_countable
     */
    public function test_should_manage_team_members()
    {
        $this->assertEmpty($this->collection);
        $this->collection->addTeamMember($this->getMockTeamMember());
        $this->assertCount(1, $this->collection);
        $this->collection->addTeamMember($this->getMockTeamMember());
        $this->assertCount(2, $this->collection);
    }

    /**
     * @depends test_should_manage_team_members
     */
    public function test_should_be_iterator()
    {
        $iterate = false;
        $this->collection->addTeamMember($this->getMockTeamMember());
        foreach ($this->collection as $element) {
            $iterate = true;
        }

        $this->assertTrue($iterate, 'The class should be iterable');
    }

    public function test_should_find_the_team_member()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $teamMember = $this->getMockTeamMember();
        $teamMember
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->addTeamMember($teamMember);
        $this->assertInstanceOfTeamMember($this->collection->findOneByName('name'));
    }

    public function test_should_filter_by_sprint()
    {
        $team = $this->getMockTeam();

        $teamMember = $this->getMockTeamMember();
        $teamMember
            ->expects($this->once())
            ->method('getTeam')
            ->will($this->returnValue($team));
        $this->collection->addTeamMember($teamMember);

        $this->assertSame($teamMember, $this->collection->filterByTeam($team));
    }
}
