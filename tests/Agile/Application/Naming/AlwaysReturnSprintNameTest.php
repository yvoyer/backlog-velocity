<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Naming;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;

final class AlwaysReturnSprintNameTest extends TestCase
{
    /**
     * @var AlwaysReturnSprintName
     */
    private $strategy;

	protected function setUp(): void
    {
        $this->strategy = new AlwaysReturnSprintName(new SprintName('name'));
    }

    public function test_it_should_return_the_next_sprint_name(): void
    {
        $this->assertEquals(new SprintName('name'), $this->strategy->nextNameOfSprint(ProjectId::uuid()));
    }
}
