<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Sprint;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;
use Star\Component\Sprint\Stub\Sprint\StubSprint;

final class StartSprintHandlerTest extends TestCase
{
    /**
     * @var SprintCollection
     */
    private $sprints;

    public function setUp()
    {
        $this->sprints = new SprintCollection(
            [
                StubSprint::withId(SprintId::fromString('s1')),
            ]
        );
    }

    public function test_it_should_start_sprint()
    {
        $this->assertFalse($this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->isStarted());

        $handler = new StartSprintHandler($this->sprints);
        $handler(StartSprint::fromString('s1', 12));

        $this->assertTrue($this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->isStarted());
    }
}
