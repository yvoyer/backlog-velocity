<?php

namespace Star\Component\Sprint;

use Star\Component\Sprint\Model\Builder\SprintBuilder;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\ProjectName;
use Star\Component\Sprint\Model\TeamName;

final class BacklogBuilder
{
    /**
     * @var Backlog
     */
    private $backlog;

    /**
     * @param Backlog $backlog
     */
    public function __construct(Backlog $backlog)
    {
        $this->backlog = $backlog;
    }

    /**
     * @param string $projectName
     *
     * @return BacklogBuilder
     */
    public function addProject($projectName)
    {
        $this->backlog->createProject(ProjectId::fromString($projectName), new ProjectName($projectName));

        return $this;
    }

    /**
     * @param string $name
     *
     * @return BacklogBuilder
     */
    public function addPerson($name)
    {
        $this->backlog->createPerson(PersonId::fromString($name), new PersonName($name));

        return $this;
    }

    /**
     * @param string $teamName
     *
     * @return BacklogBuilder
     */
    public function addTeam($teamName)
    {
        $this->backlog->createTeam(TeamId::fromString($teamName), new TeamName($teamName));

        return $this;
    }

    /**
     * @param ProjectId $projectId
     * @param \DateTimeInterface $createdAt
     *
     * @return SprintBuilder
     */
    public function createSprint(ProjectId $projectId, \DateTimeInterface $createdAt)
    {
        return new SprintBuilder($this->backlog, $this->backlog->createSprint($projectId, $createdAt), $this);
    }

    /**
     * @return Backlog
     */
    public function getBacklog()
    {
        return $this->backlog;
    }

    /**
     * @return BacklogBuilder
     */
    public static function create()
    {
        return new self(Backlog::emptyBacklog());
    }
}
