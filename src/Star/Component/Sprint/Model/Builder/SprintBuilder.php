<?php

namespace Star\Component\Sprint\Model\Builder;

use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\BacklogBuilder;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Velocity;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\ManDays;

final class SprintBuilder
{
    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * @var BackLog
     */
    private $backlog;

    /**
     * @var BacklogBuilder
     */
    private $builder;

    /**
     * @param Backlog $backlog
     * @param Sprint $sprint
     * @param BacklogBuilder $builder
     */
    public function __construct(Backlog $backlog, Sprint $sprint, BacklogBuilder $builder)
    {
        $this->backlog = $backlog;
        $this->sprint = $sprint;
        $this->builder = $builder;
    }

    /**
     * @param string $projectId
     * @param string $personName
     * @param int $manDays
     *
     * @return SprintBuilder
     */
    public function commitedMember($projectId, $personName, $manDays)
    {
        $this->backlog->commitMember(
            ProjectId::fromString($projectId), PersonId::fromString($personName), ManDays::fromInt($manDays)
        );

        return $this;
    }

    /**
     * @param int $estimatedVelocity
     * @param \DateTimeInterface $startedDate
     *
     * @return SprintBuilder
     */
    public function started($estimatedVelocity, \DateTimeInterface $startedDate = null)
    {
        if (! $startedDate instanceof \DateTimeInterface) {
            $startedDate = new \DateTime();
        }

        $this->sprint->start(Velocity::fromInt($estimatedVelocity)->toInt(), $startedDate);

        return $this;
    }

    /**
     * @return BacklogBuilder
     */
    public function backlog()
    {
        return $this->builder;
    }

    /**
     * @return \Star\Component\Sprint\Backlog
     */
    public function endBacklog()
    {
        return $this->backlog;
    }
}
