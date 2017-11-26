<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Prooph\Common\Messaging\DomainEvent;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Event\PersonJoinedTeam;
use Star\Component\Sprint\Domain\Event\ProjectWasCreated;
use Star\Component\Sprint\Domain\Event\SprintWasCreatedInProject;
use Star\Component\Sprint\Domain\Event\TeamWasCreated;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Domain\Model\TeamName;

final class ProjectBuilder
{
    /**
     * @var DomainEvent[]
     */
    private $events = [];

    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @param ProjectId $project
     * @param ProjectName $name
     */
    public function __construct(ProjectId $project, ProjectName $name)
    {
        $this->projectId = $project;
        $this->events[] = ProjectWasCreated::version1($project, $name);
    }

    /**
     * @param string $name
     *
     * @return ProjectBuilder
     */
    public static function projectIsCreated($name)
    {
        return new self(ProjectId::fromString($name), new ProjectName($name));
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function withTeam($name)
    {
        $this->events[] = TeamWasCreated::version1(
            $this->projectId,
            TeamId::fromString($name),
            new TeamName($name)
        );

        return $this;
    }

    /**
     * @param Person $person
     * @param string $teamName
     *
     * @return $this
     */
    public function withMemberInTeam(Person $person, $teamName)
    {
        $this->events[] = PersonJoinedTeam::version1(
            $this->projectId,
            $person,
            TeamId::fromString($teamName)
        );

        return $this;
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $createdAt
     *
     * @return ProjectBuilder
     */
    public function withPendingSprint($id, $name, $createdAt = null)
    {
        if (! $createdAt) {
            $createdAt = 'now';
        }

        $this->events[] = SprintWasCreatedInProject::version1(
            SprintId::fromString($id),
            $this->projectId,
            new SprintName($name),
            new \DateTimeImmutable($createdAt)
        );

        return $this;
    }

    /**
     * @return ProjectAggregate
     */
    public function getProject()
    {
        return ProjectAggregate::fromStream($this->events);
    }
}
