<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class TeamModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class TeamModel implements Team
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TeamMember[]
     */
    private $members = array();

    /**
     * @var Sprint[]
     */
    private $sprints = array();

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
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
     * Returns the available man days for the team.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        $manDays = 0;
        foreach ($this->members as $member) {
            $manDays += $member->getAvailableManDays();
        }

        return $manDays;
    }

    /**
     * @param Person $member
     */
    public function addMember(Person $member)
    {
        // @todo Use factory ->getTeamMember(Team, $memberName)
        $teamMember = new TeamMemberModel($this, $member);
        $this->members[] = $teamMember;

        return $teamMember;
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getId() not implemented yet.');
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::toArray() not implemented yet.');
    }

    /**
     * Returns whether the entity is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::isValid() not implemented yet.');
    }

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Returns the list of closed sprints.
     *
     * @return Sprint[]
     */
    public function getClosedSprints()
    {
        return array();
    }

    /**
     * @param string $sprinterName
     * @param int $manDays
     *
     * @return Sprinter
     */
    public function addSprinter($sprinterName, $manDays)
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::addSprinter() not implemented yet.');
    }
}
 