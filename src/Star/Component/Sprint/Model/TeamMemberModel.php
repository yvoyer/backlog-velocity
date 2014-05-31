<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class TeamMemberModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class TeamMemberModel implements TeamMember
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var Team
     */
    private $team;

    /**
     * @var Person
     */
    private $person;

    /**
     * @var int
     */
    private $manDays = 0;

    /**
     * @param Team    $team
     * @param Person  $person
     */
    public function __construct(Team $team, Person $person)
    {
        $this->team = $team;
        $this->person = $person;
    }

    /**
     * Returns the Person.
     *
     * @return \Star\Component\Sprint\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Returns the Team.
     *
     * @return \Star\Component\Sprint\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param object $object
     *
     * @return bool
     */
    public function isEqual($object)
    {
        return $this->person === $object || $this === $object;
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
     * @param int $manDays
     */
    public function setAvailableManDays($manDays)
    {
        $this->manDays = $manDays;
    }

    /**
     * Returns the available man days for the team member.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        return $this->manDays;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->person->getName();
    }
}
 