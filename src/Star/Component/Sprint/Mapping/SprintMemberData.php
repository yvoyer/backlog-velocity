<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class SprintMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping
 */
class SprintMemberData implements SprintMember
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
     * @param integer    $actualVelocity
     * @param Sprint     $sprint
     * @param TeamMember $teamMember
     */
    public function __construct($availableManDays, $actualVelocity, Sprint $sprint, TeamMember $teamMember)
    {
        $this->actualVelocity   = $actualVelocity;
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
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        return array();
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
     * Returns the actual velocity.
     *
     * @return integer
     */
    public function getActualVelocity()
    {
        return $this->actualVelocity;
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
     * Returns whether the entity is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        throw new \RuntimeException('Method isValid() not implemented yet.');
    }
}
