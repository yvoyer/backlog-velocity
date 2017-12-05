<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Prooph\Common\Messaging\DomainEvent;
use Star\Component\Sprint\Domain\Event\PersonJoinedTeam;
use Star\Component\Sprint\Domain\Event\ProjectWasCreated;
use Star\Component\Sprint\Domain\Event\TeamWasCreated;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
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
        $this->events[] = TeamWasCreated::version1(
            $this->projectId,
            $teamId = TeamId::fromString($name),
            new TeamName($name)
        );

        return new TeamBuilder($this, $teamId);
    }

    /**
     * @param string $memberId
     * @param string $teamId
     *
     * @return $this
     */
    public function withMemberInTeam(string $memberId, string $teamId)
    {
        $this->events[] = PersonJoinedTeam::version1(
            $this->projectId,
            MemberId::fromString($memberId),
            TeamId::fromString($teamId)
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
