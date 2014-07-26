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

    protected function create(array $elements = array())
    {
        return new self($elements);
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
        $closure = function(TeamMember $teamMember) use ($team) {
            return $teamMember->getTeam() == $team;
        };

        return $this->filter($closure)->first();
    }
}
 