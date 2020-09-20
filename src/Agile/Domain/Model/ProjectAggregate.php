<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Star\BacklogVelocity\Agile\Domain\Model\Event\PersonWasCreated;
use Star\BacklogVelocity\Agile\Domain\Model\Event\ProjectWasCreated;
use Star\BacklogVelocity\Agile\Domain\Model\Event\SprintWasCreated;
use Star\BacklogVelocity\Agile\Domain\Visitor\ProjectVisitor;

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

    public function getIdentity(): ProjectId
    {
        return ProjectId::fromString($this->aggregateId());
    }

    public function name(): ProjectName
    {
        return new ProjectName($this->name);
    }

    public function acceptProjectVisitor(ProjectVisitor $visitor): void
    {
        $visitor->visitProject($this);
    }

    public function createTeam(TeamId $teamId, TeamName $name): Team
    {
        return TeamModel::create($teamId, $name);
    }

    public function createSprint(
        SprintId $sprintId,
        SprintName $name,
        TeamId $teamId,
        \DateTimeInterface $createdAt
    ): Sprint {
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

    public function nextName(): SprintName
    {
        return new SprintName('Sprint ' . strval(count($this->sprints) + 1));
    }

    /**
     * @return DomainEvent[]
     */
    public function uncommittedEvents(): array
    {
        return $this->popRecordedEvents();
    }

    public static function emptyProject(ProjectId $id, ProjectName $name): self
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
    public static function fromStream(array $stream): self
    {
        return static::reconstituteFromHistory(new \ArrayIterator($stream));
    }

    protected function aggregateId(): string
    {
        return $this->id;
    }

    protected function whenProjectWasCreated(Event\ProjectWasCreated $event): void
    {
        $this->id = $event->projectId()->toString();
        $this->name = $event->projectName()->toString();
    }

    protected function whenSprintWasCreatedInProject(Event\SprintWasCreated $event): void
    {
        $this->createSprint($event->sprintId(), $event->name(), $event->teamId(), $event->createdAt());
    }

	protected function apply(AggregateChanged $event): void {
		if ($event instanceof ProjectWasCreated) {
			$this->whenProjectWasCreated($event);
			return;
		}

		if ($event instanceof SprintWasCreated) {
			$this->whenSprintWasCreatedInProject($event);
			return;
		}

		throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
	}
}
