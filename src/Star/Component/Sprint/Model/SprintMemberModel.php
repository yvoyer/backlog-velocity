<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class SprintMemberModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class SprintMemberModel implements SprintMember
{
    const LONG_NAME = __CLASS__;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $actualVelocity;

    /**
     * @var integer
     */
    private $availableManDays;

    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * @var TeamMember
     */
    private $teamMember;

    /**
     * @param integer    $availableManDays
     * @param Sprint     $sprint
     * @param TeamMember $teamMember
     */
    public function __construct($availableManDays, Sprint $sprint, TeamMember $teamMember)
    {
        $this->availableManDays = $availableManDays;
        $this->sprint           = $sprint;
        $this->teamMember       = $teamMember;
    }

    /**
     * Returns the unique id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the available man days.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        return $this->availableManDays;
    }

    /**
     * Returns the sprint
     *
     * @return Sprint|Sprint
     */
    public function getSprint()
    {
        return $this->sprint;
    }

    /**
     * Returns the team member
     *
     * @return TeamMember
     */
    public function getTeamMember()
    {
        return $this->teamMember;
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->teamMember->getName();
    }
}
