<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Prooph\Common\Messaging\DomainEvent;
use Star\Component\Sprint\Domain\Event\ProjectWasCreated;
use Star\Component\Sprint\Domain\Event\TeamWasCreated;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Component\Sprint\Domain\Model\ProjectName;
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
     * @param ProjectWasCreated $event
     */
    public function __construct(ProjectWasCreated $event)
    {
        $this->projectId = $event->projectId();
        $this->events[] = $event;
    }

    /**
     * @param string $name
     *
     * @return TeamBuilder
     */
    public function withTeam(string $name) :TeamBuilder
    {
        return new TeamBuilder(
            TeamWasCreated::version1(TeamId::fromString($name), new TeamName($name)),
            $this
        );
    }

    /**
     * @return ProjectAggregate
     */
    public function getProject()
    {
        return ProjectAggregate::fromStream($this->events);
    }

    public function currentProjectId() :ProjectId
    {
        return $this->projectId;
    }

    /**
     * @param string $name
     *
     * @return ProjectBuilder
     */
    public static function projectIsCreated($name)
    {
        return new self(
            ProjectWasCreated::version1(
                ProjectId::fromString($name), new ProjectName($name)
            )
        );
    }
}
