<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Star\Component\Sprint\Entity\Sprint;

/**
 * Class SprintCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class SprintCollection
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var ArrayCollection
     */
    private $collection;

    public function __construct($sprints = array())
    {
        $this->collection = new ArrayCollection($sprints);
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
}
