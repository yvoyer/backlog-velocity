<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\ProjectId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class SprintCollection implements SprintRepository
{
    const CLASS_NAME = __CLASS__;

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
     * @param string $name
     *
     * @return Sprint
     */
    public function findOneById($name)
    {
        $sprint = $this->elements->filter(function (Sprint $_sprint) use ($name) {
            return $_sprint->getId()->toString() === $name;
        })->first();

        return $sprint;
    }

    /**
     * @param Sprint $sprint
     */
    public function saveSprint(Sprint $sprint)
    {
        $this->elements[] = $sprint;
    }

    /**
     * todo add @param ProjectId $projectId
     *
     * @return Sprint[]
     */
    public function endedSprints()
    {
        return $this->elements->filter(function (Sprint $sprint) {
            return $sprint->isClosed();
        })->getValues();
    }

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint
     */
    public function activeSprintOfProject(ProjectId $projectId)
    {
        return $this->elements->filter(function (Sprint $sprint) {
            return ! $sprint->isClosed(); // todo use state isActive() which not state
        })->getValues();
    }
}
