<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Sprint;
use Traversable;

/**
 * Class SprintCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class SprintCollection implements \Countable, \IteratorAggregate
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var ArrayCollection
     */
    private $collection;

    public function __construct($sprints = array())
    {
        $this->collection = new TypedCollection('Star\Component\Sprint\Entity\Sprint', $sprints);
    }

    /**
     * Add the $sprint.
     *
     * @param Sprint $sprint
     */
    public function add(Sprint $sprint)
    {
        $this->collection->add($sprint);
    }

    /**
     * Returns all the Sprints.
     *
     * @return Sprint[]
     */
    public function all()
    {
        return $this->collection->toArray();
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return $this->collection->count();
    }
}
