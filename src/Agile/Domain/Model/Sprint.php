<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface Sprint
{
    /**
     * @return SprintId
     */
    public function getId(): SprintId;

    /**
     * Returns the actual velocity (Story point).
     *
     * @return Velocity
     */
    public function getActualVelocity(): Velocity;

    /**
     * Returns the available man days.
     *
     * @return ManDays
     */
    public function getManDays(): ManDays;

    /**
     * @return SprintName
     */
    public function getName(): SprintName;

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed(): bool;

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted(): bool;

    /**
     * Start a sprint.
     *
     * @param int $plannedVelocity
     * @param \DateTimeInterface $startedAt
     */
    public function start(int $plannedVelocity, \DateTimeInterface $startedAt);

    /**
     * Close a sprint.
     *
     * @param Velocity $actualVelocity
     * @param \DateTimeInterface $closedAt
     */
    public function close(Velocity $actualVelocity, \DateTimeInterface $closedAt);

    /**
     * Returns the real focus factor.
     *
     * @return FocusFactor
     */
    public function getFocusFactor(): FocusFactor;

    /**
     * @return Velocity
     */
    public function getPlannedVelocity(): Velocity;

    /**
     * @param MemberId $member
     * @param ManDays  $availableManDays
     *
     * @return SprintCommitment
     */
    public function commit(MemberId $member, ManDays $availableManDays): SprintCommitment;

    /**
     * @return SprintCommitment[]
     */
    public function getCommitments(): array;

    /**
     * @return ProjectId
     */
    public function projectId() :ProjectId;

    /**
     * @return TeamId
     */
    public function teamId() :TeamId;
}
