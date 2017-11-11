<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Entity\TeamMember;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Port\TeamMemberDTO;

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
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor)
    {
        $visitor->visitTeamMember($this->person);
    }

    /**
     * @param PersonId $id
     *
     * @return bool
     */
    public function matchPerson(PersonId $id)
    {
        return $id->matchIdentity($this->person->getId());
    }

    /**
     * @return TeamMemberDTO
     */
    public function teamMemberDto()
    {
        return new TeamMemberDTO($this->person->getId(), $this->person->getName());
    }
}
