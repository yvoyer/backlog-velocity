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
use Traversable;

/**
 * Class SprintMemberCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class SprintMemberCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var TypedCollection|SprintMember[]
     */
    private $collection;

    public function __construct()
    {
        $this->collection = new TypedCollection('Star\Component\Sprint\Entity\SprintMember');
    }

    /**
     * @param SprintMember $sprintMember
     */
    public function addSprinter(SprintMember $sprintMember)
    {
        $this->collection[] = $sprintMember;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * @param string $name
     *
     * @return SprintMember
     */
    public function findOneByName($name)
    {
        foreach ($this->collection as $sprinter) {
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
        return $this->collection->filter(function(SprintMember $sprintMember) use ($sprint) {
                return $sprintMember->getSprint() == $sprint;
            }
        )->first();
    }
}
 