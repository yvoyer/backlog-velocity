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
use tests\Stub\Sprint\StubSprint;
use tests\UnitTestCase;

/**
 * Class SprintCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Collection\SprintCollection
 */
class SprintCollectionTest extends UnitTestCase
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
        $sprint = $this->getMockSprint();

        $this->assertCount(0, $this->collection->allSprints(new AllObjects()));
        $this->collection->saveSprint($sprint);
        $this->assertCount(1, $this->collection->allSprints(new AllObjects()));
        $this->collection->saveSprint($sprint);
        $this->assertCount(2, $this->collection->allSprints(new AllObjects()));
    }

    public function testShouldFindTheSprint()
    {
        $sprint = StubSprint::withId($id = SprintId::fromString('name'));
        $this->assertNull($this->collection->findOneById($id));
        $this->collection->saveSprint($sprint);
        $this->assertSame($sprint, $this->collection->findOneById($id));
    }
}
