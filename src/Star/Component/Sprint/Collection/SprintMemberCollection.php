<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;

/**
 * Class SprintMemberCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class SprintMemberCollection extends TypedCollection
{
    public function __construct(array $elements = array())
    {
        parent::__construct('Star\Component\Sprint\Entity\SprintMember', $elements);
    }

    protected function create(array $elements = array())
    {
        return new self($elements);
    }

    /**
     * @param SprintMember $sprintMember
     */
    public function addSprintMember(SprintMember $sprintMember)
    {
        $this[] = $sprintMember;
    }

    /**
     * @param string $name
     *
     * @return SprintMember
     */
    public function findOneByName($name)
    {
        foreach ($this as $sprinter) {
            if ($sprinter->getName() === $name) {
                return $sprinter;
            }
        }

        return null;
    }

    /**
     * @param Sprint $sprint
     *
     * @return SprintMember
     */
    public function filterBySprint(Sprint $sprint)
    {
        $closure = function(SprintMember $sprintMember) use ($sprint) {
            return $sprintMember->getSprint() == $sprint;
        };

        return $this->filter($closure)->first();
    }
}
 