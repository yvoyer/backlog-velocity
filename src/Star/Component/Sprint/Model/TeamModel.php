<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Id\TeamId;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Plugin\Null\Entity\NullTeamMember;

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
     * @var TeamId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TypedCollection|TeamMember[]
     */
    private $members;

    /**
     * @var TypedCollection|Sprint[]
     */
    private $sprints;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->id = new TeamId($name);
        $this->name = $name;
        $this->members = new TypedCollection('Star\Component\Sprint\Entity\TeamMember');
        $this->sprints = new TypedCollection('Star\Component\Sprint\Entity\Sprint');
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @param Person $person
     *
     * @return bool
     */
    public function hasPerson(Person $person)
    {
        foreach ($this->members as $member) {
            if ($member->isEqual($person)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Person $member
     *
     * @return \Star\Component\Sprint\Entity\TeamMember
     */
    public function addMember(Person $person)
    {
        if (false === $this->hasPerson($person)) {
            $teamMember = new TeamMemberModel($this, $person);
            $this->members[] = $teamMember;

            return $teamMember;
        }

        return new NullTeamMember();
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
 