<?php declare(strict_types=1);

namespace Star\Component\Sprint\Infrastructure\Service\Naming;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\SprintName;

final class AlwaysReturnSprintNameTest extends TestCase
{
    /**
     * @var AlwaysReturnSprintName
     */
    private $strategy;

    public function setUp()
    {
        $this->strategy = new AlwaysReturnSprintName(new SprintName('name'));
    }

    public function test_it_should_return_the_next_sprint_name()
    {
        $this->assertEquals(new SprintName('name'), $this->strategy->nextSprintOfProject(ProjectId::uuid()));
    }
}
