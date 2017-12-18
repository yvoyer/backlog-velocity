<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Sprint;
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
    }

    /**
     * @param TeamId $teamId
     * @param TeamName $name
     *
     * @return Team
     */
    public function createTeam(TeamId $teamId, TeamName $name): Team
    {
        return TeamModel::create($teamId, $name);
    }

    /**
     * @param SprintId $sprintId
     * @param SprintName $name
     * @param TeamId $teamId
     * @param \DateTimeInterface $createdAt
     *
     * @return Sprint
     */
    public function createSprint(
        SprintId $sprintId,
        SprintName $name,
        TeamId $teamId,
        \DateTimeInterface $createdAt
    ) :Sprint {
        // todo should not have 2 active sprint (pending, started) with same name in project
        $sprint = SprintModel::pendingSprint(
            $sprintId,
            $name,
            $this->getIdentity(),
            $teamId,
            $createdAt
        );
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

    protected function whenSprintWasCreatedInProject(Event\SprintWasCreated $event)
    {
        $this->createSprint($event->sprintId(), $event->name(), $event->teamId(), $event->createdAt());
    }
}
