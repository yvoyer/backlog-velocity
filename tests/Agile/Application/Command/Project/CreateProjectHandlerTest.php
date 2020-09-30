<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectAggregate;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\ProjectCollection;

final class CreateProjectHandlerTest extends TestCase
{
    /**
     * @var ProjectCollection
     */
    private $projects;

	protected function setUp(): void
    {
        $this->projects = new ProjectCollection();
    }

    public function test_it_persist_a_new_empty_project(): void
    {
        $handler = new CreateProjectHandler($this->projects);
        $name = new ProjectName('name');
        $this->assertFalse($this->projects->projectExists($name));

        $handler(new CreateProject($id = ProjectId::uuid(), $name));

        $this->assertTrue($this->projects->projectExists($name));
        $project = $this->projects->getProjectWithIdentity($id);
        $this->assertInstanceOf(Project::class, $project);
        $this->assertSame('name', $project->name()->toString());
    }

    public function test_it_throw_exception_when_project_with_name_already_exists(): void
    {
        $handler = new CreateProjectHandler($this->projects);
        $name = new ProjectName('name');
        $this->projects->saveProject(ProjectAggregate::emptyProject(ProjectId::uuid(), $name));
        $this->assertTrue($this->projects->projectExists($name));

        $this->expectException(EntityAlreadyExistsException::class);
        $this->expectExceptionMessage(
            "Entity of type 'Star\BacklogVelocity\Agile\Domain\Model\Project' with 'project name' equals to 'name' already exists."
        );
        $handler(new CreateProject(ProjectId::uuid(), $name));
    }
}
