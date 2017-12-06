<?php

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\TeamName;
use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintName;

final class NullProject implements Project
{
    /**
     * @var ProjectId
     */
    private $id;

    public function __construct()
    {
        $this->id = ProjectId::fromString(uniqid('project-id-'));
    }

    /**
     * @return ProjectId
     */
    public function getIdentity()
    {
        return $this->id;
    }

    /**
     * @param TeamId $teamId
     * @param TeamName $name
     *
     * @return Team
     */
    public function createTeam(TeamId $teamId, TeamName $name): Team
    {
        return new NullTeam();
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
        return SprintModel::pendingSprint($sprintId, $name, $this->getIdentity(), $createdAt);
    }

    /**
     * @return SprintName
     */
    public function nextName()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return ProjectName
     */
    public function name(): ProjectName
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
