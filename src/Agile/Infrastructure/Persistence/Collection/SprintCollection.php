<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Filter;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\Adapter\CollectionAdapter;
use Star\Component\Collection\TypedCollection;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class SprintCollection implements SprintRepository, \Countable
{
    /**
     * @var TypedCollection|Sprint[]
     */
    private $elements;

    /**
     * @param Sprint[] $sprints
     */
    public function __construct($sprints = array())
    {
        $this->elements = new TypedCollection(Sprint::class, $sprints);
    }

    public function sprintWithName(ProjectId $projectId, SprintName $name): Sprint
    {
        // todo implement Filter
        $sprint = $this->elements->filter(function (Sprint $_sprint) use ($name, $projectId) {
            return $projectId->matchIdentity($_sprint->projectId()) && $name->equalsTo($_sprint->getName());
        })->first();

        if (! $sprint) {
            throw EntityNotFoundException::objectWithAttribute(Sprint::class, 'name', $name->toString());
        }

        return $sprint;
    }

    public function saveSprint(Sprint $sprint): void
    {
        $uniqueKey = $sprint->getId()->toString() . '_' . $sprint->projectId()->toString();
        $this->elements[$uniqueKey] = $sprint;
    }

    public function activeSprintOfProject(ProjectId $projectId): ?Sprint
    {
        // todo implement Filter
        return $this->elements->filter(function (Sprint $sprint) use ($projectId) {
            return ! $sprint->isClosed() && $projectId->matchIdentity($sprint->projectId());
            // todo use state isActive() which not state
        })->first();

        // todo throw exception when null
    }

    /**
     * @param Filter $filter
     *
     * @return Sprint[]
     */
    public function allSprints(Filter $filter): array
    {
        return $filter->applyFilter(new CollectionAdapter($this->elements));
    }

    /**
     * @param SprintId $sprintId
     *
     * @return Sprint
     * @throws EntityNotFoundException
     */
    public function getSprintWithIdentity(SprintId $sprintId): Sprint
    {
        $sprint = $this->elements->filter(
            function (Sprint $sprint) use ($sprintId) {
                return $sprintId->matchIdentity($sprint->getId());
            })
            ->first();
        if (! $sprint) {
            throw EntityNotFoundException::objectWithIdentity($sprintId);
        }

        return $sprint;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * @param TeamId $teamId
     * @param \DateTimeInterface $before
     *
     * @return FocusFactor[]
     */
    public function estimatedFocusOfPastSprints(TeamId $teamId, \DateTimeInterface $before): array
    {
        return array_map(
            function(Sprint $sprint) {
                return $sprint->getFocusFactor();
            },
            $this->elements->filter(function (Sprint $sprint) use ($teamId) {
                return $teamId->matchIdentity($sprint->teamId()) && $sprint->isClosed();
            })->getValues()
        );
    }
}
