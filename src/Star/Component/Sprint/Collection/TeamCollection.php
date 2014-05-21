<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;
use Traversable;

/**
 * Class TeamCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class TeamCollection implements \Countable, \IteratorAggregate, TeamRepository
{
    /**
     * @var TypedCollection|Team[]
     */
    private $collection;

    public function __construct()
    {
        $this->collection = new TypedCollection('Star\Component\Sprint\Entity\Team');
    }

    /**
     * @param Team $team
     */
    public function add($team)
    {
        $this->collection->add($team);
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
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Team|null
     */
    public function findOneByName($name)
    {
        foreach ($this->collection as $team) {
            if ($team->getName() === $name) {
                return $team;
            }
        }

        return null;
    }
}
 