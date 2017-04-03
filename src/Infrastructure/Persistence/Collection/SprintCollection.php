<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Collection\Adapter\CollectionAdapter;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\Filter;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\SprintName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class SprintCollection implements SprintRepository
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

    /**
     * @param ProjectId $projectId
     * @param SprintName $name
     *
     * @return Sprint
     */
    public function sprintWithName(ProjectId $projectId, SprintName $name)
    {
        // todo implement Filter
        $sprint = $this->elements->filter(function (Sprint $_sprint) use ($name, $projectId) {
            return $projectId->matchIdentity($_sprint->projectId()) && $name->equalsTo($_sprint->getName());
        })->first();

        return $sprint;
    }

    /**
     * @param Sprint $sprint
     */
    public function saveSprint(Sprint $sprint)
    {
        $uniqueKey = $sprint->getId()->toString() . '_' . $sprint->projectId()->toString();
        $this->elements[$uniqueKey] = $sprint;
    }

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint[]
     */
    public function endedSprints(ProjectId $projectId)
    {
        // todo implement Filter
        return $this->elements->filter(function (Sprint $sprint) use ($projectId) {
            return $sprint->matchProject($projectId) && $sprint->isClosed();
        })->getValues();
    }

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint
     */
    public function activeSprintOfProject(ProjectId $projectId)
    {
        // todo implement Filter
        return $this->elements->filter(function (Sprint $sprint) use ($projectId) {
            return ! $sprint->isClosed() && $sprint->matchProject($projectId);
            // todo use state isActive() which not state
        })->first();

        // todo throw exception when null
    }

    /**
     * @param Filter $filter
     *
     * @return Sprint[]
     */
    public function allSprints(Filter $filter)
    {
        return $filter->applyFilter(new CollectionAdapter($this->elements));
    }
}
