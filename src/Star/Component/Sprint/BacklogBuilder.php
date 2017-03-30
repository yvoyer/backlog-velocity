<?php

namespace Star\Component\Sprint;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Collection\ProjectCollection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\Model\Builder\SprintBuilder;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\ProjectName;
use Star\Component\Sprint\Model\TeamName;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Plugin\InMemory\InMemoryPlugin;

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
     * @param string $projectId
     * @param \DateTimeInterface $createdAt = null
     *
     * @return SprintBuilder
     */
    public function createSprint($projectId, \DateTimeInterface $createdAt = null)
    {
        if (! $createdAt instanceof \DateTimeInterface) {
            $createdAt = new \DateTimeImmutable();
        }

        return new SprintBuilder(
            $this->backlog,
            $this->backlog->createSprint(ProjectId::fromString($projectId), $createdAt),
            $this
        );
    }

    /**
     * @return Backlog
     */
    public function getBacklog()
    {
        return $this->backlog;
    }

    /**
     * @param Backlog $backlog
     *
     * @return BacklogBuilder
     */
    public static function fromBacklog(Backlog $backlog)
    {
        return new self($backlog);
    }

    /**
     * @param BacklogPlugin|null $plugin
     *
     * @return BacklogBuilder
     */
    public static function fromPlugin(BacklogPlugin $plugin)
    {
        return new self(Backlog::fromPlugin($plugin));
    }
}
