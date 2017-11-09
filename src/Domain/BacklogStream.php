<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Domain;

use Star\Component\Sprint\Event\ProjectWasCreated;
use Star\Component\Sprint\Event\SprintWasCreatedInProject;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ProjectAggregate;
use Star\Component\Sprint\Model\ProjectName;
use Star\Component\Sprint\Model\SprintName;

final class BacklogStream
{
    /**
     * @var
     */
    private $events = [];

    private function __construct()
    {
    }

    /**
     * @param string $name
     *
     * @return BacklogStream
     */
    public static function projectIsCreated($name)
    {
        $stream = new self();
        $stream->events[] = ProjectWasCreated::version1(
            ProjectId::fromString($name),
            new ProjectName($name)
        );

        return $stream;
    }

    public function withTeam($name, $members)
    {

        return $this;
    }

    public function withPerson($name)
    {

        return $this;
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $createdAt
     *
     * @return SprintStream
     */
    public function withPendingSprint($id, $name, $createdAt = null)
    {
        if (! $createdAt) {
            $createdAt = 'now';
        }

        $this->events[] = SprintWasCreatedInProject::version1(
            SprintId::fromString($id),
            new SprintName($name),
            new \DateTimeImmutable($createdAt)
        );

        return new SprintStream($this);
    }

    /**
     * @return ProjectAggregate
     */
    public function getProject()
    {
        return ProjectAggregate::fromStream($this->events);
    }
}
