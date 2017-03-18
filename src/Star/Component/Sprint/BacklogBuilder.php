<?php

namespace Star\Component\Sprint;

use Star\Component\Sprint\Event\ProjectWasCreated;
use Star\Component\Sprint\Event\TeamWasCreated;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\ProjectName;

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
        $this->backlog->createPerson(ProjectWasCreated::version1($memberName));

        return $this;
    }

    /**
     * @param string $teamName
     *
     * @return BacklogBuilder
     */
    public function addTeam($teamName)
    {
    //    $this->backlog->apply(TeamWasCreated::version1($teamName));

        return $this;
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
