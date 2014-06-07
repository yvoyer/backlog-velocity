<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class TeamMemberCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class TeamMemberCollection extends TypedCollection
{
    public function __construct(array $elements = array())
    {
        parent::__construct('Star\Component\Sprint\Entity\TeamMember', $elements);
    }

    /**
     * @param TeamMember $member
     */
    public function addTeamMember(TeamMember $member)
    {
        $this->add($member);
    }

    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return TeamMember|null
     */
    public function findOneByName($name)
    {
        foreach ($this as $member) {
            if ($member->getName() === $name) {
                return $member;
            }
        }

        return null;
    }

    /**
     * @param Team $team
     *
     * @return TeamMember
     */
    public function filterByTeam(Team $team)
    {
        return $this->filter(function(TeamMember $teamMember) use ($team) {
                return $teamMember->getTeam() == $team;
            }
        )->first();
    }
}
 