<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Sprint;

/**
 * Class SprintCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class SprintCollection extends TypedCollection
{
    const CLASS_NAME = __CLASS__;

    public function __construct($sprints = array())
    {
        parent::__construct('Star\Component\Sprint\Entity\Sprint', $sprints);
    }

    /**
     * Add the $sprint.
     *
     * @param Sprint $sprint
     *
     * @deprecated todo use addSprint instead
     */
    public function add($sprint)
    {
        $this->addSprint($sprint);
    }

    /**
     * Add the $sprint.
     *
     * @param Sprint $sprint
     */
    public function addSprint(Sprint $sprint)
    {
        $this[] = $sprint;
    }

    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function findOneByName($name)
    {
        foreach ($this as $sprint) {
            if ($sprint->getName() === $name) {
                return $sprint;
            }
        }

        return null;
    }
}
