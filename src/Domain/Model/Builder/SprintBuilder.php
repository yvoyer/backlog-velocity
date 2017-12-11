<?php
//
//namespace Star\Component\Sprint\Domain\Model\Builder;
//
//use Star\Component\Sprint\Domain\Backlog;
//use Star\Component\Sprint\Domain\BacklogBuilder;
//use Star\Component\Sprint\Domain\Entity\Sprint;
//use Star\Component\Sprint\Domain\Model\Velocity;
//use Star\Component\Sprint\Domain\Model\Identity\PersonId;
//use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
//use Star\Component\Sprint\Domain\Model\ManDays;
//
//final class SprintBuilder
//{
//    /**
//     * @var Sprint
//     */
//    private $sprint;
//
//    /**
//     * @var Backlog
//     */
//    private $backlog;
//
//    /**
//     * @var BacklogBuilder
//     */
//    private $builder;
//
//    /**
//     * @param Backlog $backlog
//     * @param Sprint $sprint
//     * @param BacklogBuilder $builder
//     */
//    public function __construct(Backlog $backlog, Sprint $sprint, BacklogBuilder $builder)
//    {
//        $this->backlog = $backlog;
//        $this->sprint = $sprint;
//        $this->builder = $builder;
//    }
//
//    /**
//     * @param string $projectId
//     * @param string $personName
//     * @param int $manDays
//     *
//     * @return SprintBuilder
//     */
//    public function commitedMember($projectId, $personName, $manDays)
//    {
//        $this->backlog->commitMember(
//            ProjectId::fromString($projectId), PersonId::fromString($personName), ManDays::fromInt($manDays)
//        );
//
//        return $this;
//    }
//
//    /**
//     * @param int $estimatedVelocity
//     * @param \DateTimeInterface $startedDate
//     *
//     * @return SprintBuilder
//     */
//    public function started($estimatedVelocity, \DateTimeInterface $startedDate = null)
//    {
//        if (! $startedDate instanceof \DateTimeInterface) {
//            $startedDate = new \DateTime();
//        }
//
//        $this->sprint->start(Velocity::fromInt($estimatedVelocity)->toInt(), $startedDate);
//
//        return $this;
//    }
//
//    /**
//     * @param int $actual
//     * @param \DateTimeInterface|null $endedAt
//     *
//     * @return SprintBuilder
//     */
//    public function ended($actual, \DateTimeInterface $endedAt = null)
//    {
//        if (! $endedAt instanceof \DateTimeInterface) {
//            $endedAt = new \DateTime();
//        }
//
//        $this->sprint->close(Velocity::fromInt($actual)->toInt(), $endedAt);
//
//        return $this;
//    }
//
//    /**
//     * @return BacklogBuilder
//     */
//    public function backlog()
//    {
//        return $this->builder;
//    }
//
//    /**
//     * @return Backlog
//     */
//    public function endBacklog()
//    {
//        return $this->backlog;
//    }
//}
