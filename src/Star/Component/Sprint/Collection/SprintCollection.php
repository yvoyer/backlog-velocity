<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;

/**
 * Class SprintCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class SprintCollection extends TypedCollection implements SprintRepository
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
     * Returns all the Sprints.
     *
     * @return Sprint[]
     * @deprecated todo remove
     */
    public function all()
    {
        return $this->toArray();
    }

    /**
     * Returns all the object from one repository.
     *
     * @return array
     */
    public function findAll()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param mixed $id
     *
     * @return object
     */
    public function find($id)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Returns the object matching the $criteria.
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
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
