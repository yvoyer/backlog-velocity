<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Repository\Filters\AllObjects;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Stub\Sprint\StubSprint;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class SprintCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SprintCollection|Sprint[]
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new SprintCollection();
    }

    public function testShouldAddSprint()
    {
        $this->assertCount(0, $this->collection->allSprints(new AllObjects()));
        $this->collection->saveSprint(StubSprint::withId(SprintId::uuid()));
        $this->assertCount(1, $this->collection->allSprints(new AllObjects()));
        $this->collection->saveSprint($duplicate = StubSprint::withId(SprintId::uuid()));
        $this->assertCount(2, $this->collection->allSprints(new AllObjects()));
        $this->collection->saveSprint($duplicate);
        $this->assertCount(2, $this->collection->allSprints(new AllObjects()));
    }

    public function testShouldFindTheSprint()
    {
        $sprint = StubSprint::withId($id = SprintId::uuid());
        $this->assertNull($this->collection->sprintWithName($sprint->projectId(), $sprint->getName()));
        $this->collection->saveSprint($sprint);
        $this->assertSame($sprint, $this->collection->sprintWithName($sprint->projectId(), $sprint->getName()));
    }
}
