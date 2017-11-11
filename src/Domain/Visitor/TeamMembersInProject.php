<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Visitor;

use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;

final class TeamMembersInProject implements ProjectVisitor
{
    /**
     * @var Person[]
     */
    private $persons = [];

    /**
     * @return PersonId[]
     */
    public function getPersons()
    {
        return array_map(
            function (Person $person) {
                return $person->getId();
            },
            $this->persons
        );
    }

    /**
     * @param Project $project
     */
    public function visitProject(Project $project)
    {
        $this->persons = [];
    }

    /**
     * @param Team $team
     */
    public function visitTeam(Team $team)
    {
    }

    /**
     * @param Person $member
     */
    public function visitTeamMember(Person $member)
    {
        $this->persons[$member->getId()->toString()] = $member;
    }
}
