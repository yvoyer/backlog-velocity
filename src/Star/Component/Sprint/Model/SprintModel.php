<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Calculator\FocusCalculator;
use Star\Component\Sprint\Collection\SprintMemberCollection;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Component\Sprint\Exception\Sprint\AlreadyCommittedSprintMemberException;
use Star\Component\Sprint\Exception\Sprint\AlreadyStartedSprintException;
use Star\Component\Sprint\Exception\Sprint\NoSprintMemberException;
use Star\Component\Sprint\Exception\Sprint\SprintNotClosedException;

/**
 * Class SprintModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class SprintModel /* todo extends AggregateRoot */implements Sprint
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Team
     */
    private $team;

    /**
     * @var SprintMember[]
     */
    private $sprintMembers;

    /**
     * @var integer
     */
    private $actualVelocity = 0;

    /**
     * @var int
     */
    private $estimatedVelocity = 0;

    /**
     * @var int
     */
    private $status = self::STATUS_INACTIVE;

    /**
     * @param SprintId $id
     * @param string $name
     * @param Team $team
     * @throws InvalidArgumentException
     */
    public function __construct(SprintId $id, $name, Team $team)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("The name can't be empty.");
        }

        $this->id = $id->toString();
        $this->name = $name;
        $this->team = $team;
        $this->sprintMembers = new ArrayCollection();
    }

    /**
     * Returns the unique id.
     *
     * @return SprintId
     */
    public function getId()
    {
        return SprintId::fromString($this->id);
    }

    /**
     * Returns the Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Returns the real focus factor.
     *
     * @throws \Star\Component\Sprint\Exception\Sprint\SprintNotClosedException
     * @return integer
     */
    public function getFocusFactor()
    {
        if (false === $this->isClosed()) {
            throw new SprintNotClosedException('The sprint is not closed, the focus cannot be determined.');
        }

        $calculator = new FocusCalculator();
        return $calculator->calculate($this->getManDays(), $this->getActualVelocity());
    }

    /**
     * @return integer
     */
    public function getEstimatedVelocity()
    {
        return $this->estimatedVelocity;
    }

    /**
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity()
    {
        return $this->actualVelocity;
    }

    /**
     * Returns the available man days.
     *
     * @return int
     */
    public function getManDays()
    {
        $availableManDays = 0;
        foreach ($this->sprintMembers as $sprinter) {
            $availableManDays += $sprinter->getAvailableManDays();
        }

        return $availableManDays;
    }

    /**
     * @return SprintMember[]
     */
    public function getSprintMembers()
    {
        return $this->sprintMembers;
    }

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted()
    {
        return $this->status === self::STATUS_STARTED;
    }

    /**
     * @param int $estimatedVelocity
     * @throws \Star\Component\Sprint\Exception\Sprint\NoSprintMemberException
     * @throws \Star\Component\Sprint\Exception\Sprint\AlreadyStartedSprintException
     */
    public function start($estimatedVelocity)
    {
        if ($this->isStarted()) {
            throw new AlreadyStartedSprintException('The sprint is already started.');
        }

        if (0 === $this->sprintMembers->count()) {
            throw new NoSprintMemberException('Cannot start a sprint with no sprint members.');
        }

        $this->status = self::STATUS_STARTED;
        $this->estimatedVelocity = $estimatedVelocity;
    }

    /**
     * @param TeamMember $member
     * @param int $availableManDays
     *
     * @throws \Star\Component\Sprint\Exception\Sprint\AlreadyCommittedSprintMemberException
     * @return SprintMember
     */
    public function commit(TeamMember $member, $availableManDays)
    {
        $sprintMembersList = new SprintMemberCollection($this->sprintMembers->toArray());
        $teamMemberName = $member->getName();
        if ($sprintMembersList->findOneByName($teamMemberName)) {
            throw new AlreadyCommittedSprintMemberException("The sprint member '{$teamMemberName}' is already committed.");
        }

        $sprintMember = new SprintMemberModel($availableManDays, $this, $member);
        $this->sprintMembers->add($sprintMember);

        return $sprintMember;
    }

    /**
     * Close a sprint.
     *
     * @param integer $actualVelocity
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function close($actualVelocity)
    {
        if ($this->isClosed()) {
            throw new InvalidArgumentException('Cannot close a sprint that is already closed.');
        }

        if (false === $this->isStarted() && false === $this->isClosed()) {
            throw new InvalidArgumentException('Cannot close a sprint that is not started.');
        }

        $this->status = self::STATUS_CLOSED;
        $this->actualVelocity = $actualVelocity;
    }
}
