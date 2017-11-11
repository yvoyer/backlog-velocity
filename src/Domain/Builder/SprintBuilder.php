<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Star\Component\Sprint\Domain\Event\SprintWasCreatedInProject;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;

final class SprintBuilder
{
    /**
     * @var ProjectBuilder
     */
    private $builder;

    /**
     * @var array
     */
    private $events = [];

    /**
     * @param ProjectBuilder $builder
     * @param SprintId $sprintId
     * @param ProjectId $projectId
     * @param SprintName $name
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(
        ProjectBuilder $builder,
        SprintId $sprintId,
        ProjectId $projectId,
        SprintName $name,
        \DateTimeInterface $createdAt
    ) {
        $this->builder = $builder;
        $this->events[] = SprintWasCreatedInProject::version1(
            $sprintId, $projectId, $name, $createdAt
        );
    }

    public function memberIsCommitted()
    {

        return $this;
    }

    public function isStarted()
    {

        return $this;
    }

    public function isClosed()
    {

        return $this;
    }

    /**
     * @return SprintModel
     */
    public function buildSprint()
    {
        return SprintModel::fromStream($this->events);
    }

    /**
     * @return \Star\Component\Sprint\Domain\Model\ProjectAggregate
     */
    public function getProject()
    {
        return $this->builder->getProjectId();
    }
}
