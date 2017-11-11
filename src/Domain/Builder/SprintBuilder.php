<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Star\Component\Sprint\Domain\Event\SprintWasCreatedInProject;
use Star\Component\Sprint\Domain\Event\TeamMemberCommitedToSprint;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\ManDays;
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
     * @var SprintId
     */
    private $sprintId;

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
        $this->sprintId = $sprintId;
        $this->builder = $builder;
        $this->events[] = SprintWasCreatedInProject::version1(
            $this->sprintId, $projectId, $name, $createdAt
        );
    }

    /**
     * @param string $personName
     * @param int $manDays
     *
     * @return $this
     */
    public function withCommittedMember(string $personName, int $manDays)
    {
        $this->events[] = TeamMemberCommitedToSprint::version1(
            $this->sprintId,
            PersonId::fromString($personName),
            ManDays::fromInt($manDays)
        );

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
