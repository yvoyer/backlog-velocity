<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;
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

    /**
     * @var TeamCollection
     */
    private $teams;

    public function setUp()
    {
        $this->handler = new CreateSprintHandler(
            $this->projects = new ProjectCollection(),
            $this->sprints = new SprintCollection(),
            $this->teams = new TeamCollection(),
            new AlwaysReturnSprintName(new SprintName('some name'))
        );
    }

    public function test_it_should_throw_exception_when_team_not_found() {
        $project = new NullProject();
        $this->projects->saveProject($project);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            "Object of class 'Star\Component\Sprint\Domain\Entity\Team' with identity 'tid' could not be found."
        );
        $handler = $this->handler;
        $handler(
            new CreateSprint(SprintId::uuid(), $project->getIdentity(), TeamId::fromString('tid'))
        );
    }

    public function test_it_create_sprint()
    {
        $this->teams->saveTeam(TeamModel::fromString('tid', 'tname', new NullProject()));
        $project = new NullProject();
        $this->projects->saveProject($project);

        $this->assertCount(0, $this->sprints);

        $handler = $this->handler;
        $handler(
            new CreateSprint($sprintId = SprintId::uuid(), $project->getIdentity(), TeamId::fromString('tid'))
        );

        $this->assertCount(1, $this->sprints);
        $this->assertInstanceOf(Sprint::class, $sprint = $this->sprints->getSprintWithIdentity($sprintId));
        $this->assertSame('some name', $sprint->getName()->toString());
        $this->assertSame('tid', $sprint->teamId()->toString());
        $this->assertContains('project-id-', $sprint->projectId()->toString());
    }
}
