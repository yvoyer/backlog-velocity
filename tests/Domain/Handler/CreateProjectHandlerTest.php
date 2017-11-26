<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\ProjectCollection;

final class CreateProjectHandlerTest extends TestCase
{
    /**
     * @var ProjectCollection
     */
    private $projects;

    public function setUp()
    {
        $this->projects = new ProjectCollection();
    }

    public function test_it_persist_a_new_empty_project()
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

    public function test_it_throw_exception_when_project_with_name_already_exists()
    {
        $handler = new CreateProjectHandler($this->projects);
        $name = new ProjectName('name');
        $this->projects->saveProject(ProjectAggregate::emptyProject(ProjectId::uuid(), $name));
        $this->assertTrue($this->projects->projectExists($name));

        $this->expectException(EntityAlreadyExistsException::class);
        $this->expectExceptionMessage(
            "Entity of type 'Star\Component\Sprint\Domain\Entity\Project' with 'project name' equals to 'name' already exists."
        );
        $handler(new CreateProject(ProjectId::uuid(), $name));
    }
}
