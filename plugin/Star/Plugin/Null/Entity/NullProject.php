<?php

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\SprintName;

final class NullProject implements Project
{
    /**
     * @var ProjectId
     */
    private $id;

    public function __construct()
    {
        $this->id = ProjectId::fromString(uniqid());
    }

    /**
     * @return ProjectId
     */
    public function getIdentity()
    {
        return $this->id;
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
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return SprintName
     */
    public function nextName()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
