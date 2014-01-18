<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;

/**
 * Class TeamMemberModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class TeamMemberModel
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var Team
     */
    private $team;

    /**
     * @var Person
     */
    private $person;

    /**
     * @var integer
     */
    private $availableManDays;

    /**
     * @param Team    $team
     * @param Person  $person
     * @param integer $availableManDays
     */
    public function __construct(Team $team, Person $person, $availableManDays)
    {
        $this->team = $team;
        $this->person = $person;
        $this->availableManDays = $availableManDays;
    }

    /**
     * Returns the AvailableManDays.
     *
     * @return int
     */
    public function getAvailableManDays()
    {
        return $this->availableManDays;
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
}
 