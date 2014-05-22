<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Collection;

use Star\Component\Sprint\Collection\TeamMemberCollection;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamMemberCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Collection
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
}
 