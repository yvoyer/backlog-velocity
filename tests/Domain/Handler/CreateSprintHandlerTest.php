<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;
use Star\Component\Sprint\Infrastructure\Service\Naming\AlwaysReturnSprintName;
use Star\Plugin\Null\Entity\NullProject;

final class CreateSprintHandlerTest extends TestCase
{
    /**
     * @var CreateSprintHandler
     */
    private $handler;

    /**
     * @var ProjectCollection
     */
    private $projects;

    /**
     * @var SprintCollection
     */
    private $sprints;

    public function setUp()
    {
        $this->handler = new CreateSprintHandler(
            $this->projects = new ProjectCollection(),
            $this->sprints = new SprintCollection(),
            new AlwaysReturnSprintName(new SprintName('some name'))
        );
    }

    public function test_it_create_sprint()
    {
        $project = new NullProject();
        $this->projects->saveProject($project);

        $this->assertCount(0, $this->sprints);

        $handler = $this->handler;
        $handler(new CreateSprint($project->getIdentity(), $sprintId = SprintId::uuid()));

        $this->assertCount(1, $this->sprints);
        $this->assertInstanceOf(Sprint::class, $sprint = $this->sprints->getSprintWithIdentity($sprintId));
        $this->assertSame('some name', $sprint->getName()->toString());
    }
}
