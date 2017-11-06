<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Component\Sprint\Domain\Model\ProjectName;

final class ProjectDTO
{
    /**
     * @var ProjectId
     */
    private $id;

    /**
     * @var ProjectName
     */
    private $name;

    /**
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id = ProjectId::fromString($id);
        $this->name = new ProjectName($name);
    }

    /**
     * @return string
     */
    public function id() :string
    {
        return $this->id->toString();
    }

    /**
     * @return string
     */
    public function name() :string
    {
        return $this->name->toString();
    }

    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return ProjectDTO
     */
    public static function fromDomain(ProjectId $id, ProjectName $name) :ProjectDTO
    {
        return new self($id->toString(), $name->toString());
    }

    /**
     * @param ProjectAggregate $project
     *
     * @return ProjectDTO
     */
    public static function fromAggregate(ProjectAggregate $project) :ProjectDTO
    {
        return self::fromDomain($project->getIdentity(), $project->name());
    }
}
