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
    public function getId();

    /**
     * Returns the actual velocity (Story point).
     *
     * @return int todo return StoryPoint
     */
    public function getActualVelocity();

    /**
     * Returns the available man days.
     *
     * @return ManDays
     */
    public function getManDays();

    /**
     * @return SprintName
     */
    public function getName();

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed();

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted();

    /**
     * Start a sprint.
     *
     * @param int $estimatedVelocity
     * @param \DateTimeInterface $startedAt
     */
    public function start(int $estimatedVelocity, \DateTimeInterface $startedAt);

    /**
     * Close a sprint.
     *
     * @param integer $actualVelocity
     * @param \DateTimeInterface $endedAt
     */
    public function close(int $actualVelocity, \DateTimeInterface $endedAt);

    /**
     * Returns the real focus factor.
     *
     * @return integer todo FocusFactor
     */
    public function getFocusFactor();

    /**
     *
     * @return integer todo return StoryPoint
     */
    public function getEstimatedVelocity();

    /**
     * @param MemberId $member
     * @param ManDays  $availableManDays
     *
     * @return SprintCommitment
     */
    public function commit(MemberId $member, ManDays $availableManDays);

    /**
     * @return SprintCommitment[]
     */
    public function getCommitments();

    /**
     * @return ProjectId
     */
    public function projectId() :ProjectId;

    /**
     * @return TeamId
     */
    public function teamId() :TeamId;

    /**
     * @param ProjectId $projectId
     *
     * @return bool
     */
    public function matchProject(ProjectId $projectId);
}
