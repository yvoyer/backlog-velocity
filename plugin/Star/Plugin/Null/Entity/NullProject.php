<?php

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;

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
     * @param \DateTimeInterface $createdAt
     *
     * @return Sprint
     */
    public function createSprint(SprintId $sprintId, \DateTimeInterface $createdAt)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
