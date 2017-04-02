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
use Star\Component\Sprint\Port\TeamMemberDTO;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamMemberModel implements TeamMember
{
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
     * @param Team    $team
     * @param Person  $person
     */
    public function __construct(Team $team, Person $person)
    {
        $this->team = $team;
        $this->person = $person;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function matchPerson($name)
    {
        return $this->person->getName() === $name;
    }

    /**
     * @return TeamMemberDTO
     */
    public function teamMemberDto()
    {
        return new TeamMemberDTO($this->person->getId(), $this->person->getName());
    }
}
