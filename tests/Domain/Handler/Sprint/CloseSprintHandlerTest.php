<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Sprint;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Builder\SprintBuilder;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;

final class CloseSprintHandlerTest extends TestCase
{
    /**
     * @var CloseSprintHandler
     */
    private $handler;

    /**
     * @var SprintCollection
     */
    private $sprints;

    public function setUp()
    {
        $this->handler = new CloseSprintHandler(
            $this->sprints = new SprintCollection()
        );
    }

    public function test_it_should_end_sprint()
    {
        $this->sprints->saveSprint(
            $sprint = SprintBuilder::pending('sid', 'pid', 'tid')
                ->committedMember('mid', 12)
                ->started(34)
                ->buildSprint()
        );

        $handler = $this->handler;

        $handler(CloseSprint::fromString('sid', 56));
        $this->assertSame(56, $sprint->getActualVelocity());
        $this->assertInstanceOf(\DateTimeInterface::class, $sprint->endedAt());
        $this->assertSame(date('Y-m-d'), $sprint->endedAt()->format('Y-m-d'));
    }

    public function test_it_should_throw_exception_when_sprint_is_started()
    {
        $handler = $this->handler;
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            "Object of class 'Star\Component\Sprint\Domain\Model\SprintModel' with identity 'sid' could not be found."
        );
        $handler(CloseSprint::fromString('sid', 34));
    }
}
