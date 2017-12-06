<?php

namespace Star\Component\Sprint\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Event;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;

// todo put final remove from entity
class ProjectAggregate extends AggregateRoot implements Project
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Sprint[]|Collection
     */
    private $sprints = [];

    /**
     * @var Team[]|Collection
     */
    private $teams = []; // todo check whether we keep

    protected function __construct()
    {
        $this->sprints = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    /**
     * @return ProjectId
     */
    public function getIdentity()
    {
        return ProjectId::fromString($this->aggregateId());
    }

    /**
     * @return ProjectName
     */
    public function name() :ProjectName
    {
        return new ProjectName($this->name);
    }

    /**
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor)
    {
        $visitor->visitProject($this);
        foreach ($this->teams as $team) {
            $team->acceptProjectVisitor($visitor);
        }
    }

    /**
     * @return SprintId[]
     */
    public function sprints()
    {
        return $this->sprints->map(
            function (Sprint $sprint) {
                return $sprint->getId();
            }
        )->getValues();
    }

    /**
     * @return TeamId[]
     */
    public function teams()
    {
        return $this->teams->map(
            function (Team $team) {
                return $team->getId();
            }
        )->getValues();
    }

    /**
     * @param Sprint $sprint
     *
     * @internal Used only for building
     */
    public function addSprint(Sprint $sprint)
    {
        $this->sprints[] = $sprint;
    }

    /**
     * @param SprintId $sprintId
     * @param SprintName $name
     * @param \DateTimeInterface $createdAt
     *
     * @return Sprint
     */
    public function createSprint(SprintId $sprintId, SprintName $name, \DateTimeInterface $createdAt)
    {
        // todo should not have 2 active sprint (pending, started) with same name in project
        $sprint = SprintModel::pendingSprint($sprintId, $name, $this->getIdentity(), $createdAt);
        $this->sprints[] = $sprint;

        return $sprint;
    }

    /**
     * @return SprintName
     */
    public function nextName()
    {
        return new SprintName('Sprint ' . strval(count($this->sprints) + 1));
    }

    /**
     * @return DomainEvent[]
     */
    public function uncommittedEvents()
    {
        return $this->popRecordedEvents();
    }

    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return ProjectAggregate
     */
    public static function emptyProject(ProjectId $id, ProjectName $name)
    {
        $project = new self();
        $project->recordThat(Event\ProjectWasCreated::version1($id, $name));

        return $project;
    }

    /**
     * @param array $stream
     *
     * @return static
     */
    public static function fromStream(array $stream)
    {
        return static::reconstituteFromHistory(new \ArrayIterator($stream));
    }

    /**
     * @param TeamId $teamId
     *
     * @return Team
     * @throws EntityNotFoundException
     */
    private function getTeamWithId(TeamId $teamId)
    {
        $team = $this->teams->filter(
            function (Team $team) use ($teamId) {
                return $teamId->matchIdentity($team->getId());
            }
        )->first();
        if (! $team) {
            throw EntityNotFoundException::objectWithIdentity($teamId);
        }

        return $team;
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->id;
    }

    protected function whenProjectWasCreated(Event\ProjectWasCreated $event)
    {
        $this->id = $event->projectId()->toString();
        $this->name = $event->projectName()->toString();
    }

    protected function whenSprintWasCreatedInProject(Event\SprintWasCreatedInProject $event)
    {
        $this->createSprint($event->sprintId(), $event->name(), $event->createdAt());
    }

    protected function whenTeamWasCreated(Event\TeamWasCreated $event)
    {
        // todo do something, not used
        $this->teams[] = new TeamModel(
            $event->teamId(),
            $event->name(),
            $this
        );
    }

    protected function whenPersonJoinedTeam(Event\PersonJoinedTeam $event)
    {
        $team = $this->getTeamWithId($event->teamId());
        $team->addTeamMember(
            new class($event->memberId()) implements Member {
                /**
                 * @var MemberId
                 */
                private $memberId;

                /**
                 * @param MemberId $memberId
                 */
                public function __construct(MemberId $memberId)
                {
                    $this->memberId = $memberId;
                }

                /**
                 * @return MemberId
                 */
                public function memberId(): MemberId
                {
                    return $this->memberId;
                }
            }
        );
    }
}
