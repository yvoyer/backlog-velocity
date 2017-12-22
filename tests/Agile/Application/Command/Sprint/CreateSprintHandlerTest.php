<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Application\Naming\AlwaysReturnSprintName;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\TeamCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null\NullProject;

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
            "Object of class 'Star\BacklogVelocity\Agile\Domain\Model\Team' with identity 'tid' could not be found."
        );
        $handler = $this->handler;
        $handler(
            new CreateSprint(SprintId::uuid(), $project->getIdentity(), TeamId::fromString('tid'))
        );
    }

    public function test_it_create_sprint()
    {
        $this->teams->saveTeam(TeamModel::fromString('tid', 'tname'));
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
