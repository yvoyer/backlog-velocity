<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Stub\StubSprint;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;

final class StartSprintHandlerTest extends TestCase
{
    /**
     * @var SprintCollection
     */
    private $sprints;

	protected function setUp(): void
    {
        $this->sprints = new SprintCollection(
            [
                StubSprint::withId(SprintId::fromString('s1')),
            ]
        );
    }

    public function test_it_should_start_sprint(): void
    {
        $this->assertFalse($this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->isStarted());

        $handler = new StartSprintHandler($this->sprints);
        $handler(StartSprint::fromString('s1', 12));

        $this->assertTrue($this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->isStarted());
    }
}
