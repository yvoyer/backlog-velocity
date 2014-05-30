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
class TeamMemberCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var TypedCollection|TeamMember[]
     */
    private $collection;

    public function __construct()
    {
        $this->collection = new TypedCollection('Star\Component\Sprint\Entity\TeamMember');
    }

    /**
     * @param TeamMember $member
     */
    public function addTeamMember(TeamMember $member)
    {
        $this->collection->add($member);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
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
        foreach ($this->collection as $member) {
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
        return $this->collection->filter(function(TeamMember $teamMember) use ($team) {
                return $teamMember->getTeam() == $team;
            }
        )->first();
    }
}
 