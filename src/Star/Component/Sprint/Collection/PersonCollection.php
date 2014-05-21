<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Traversable;

/**
 * Class PersonCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class PersonCollection implements \Countable, \IteratorAggregate, MemberRepository
{
    /**
     * @var TypedCollection|Person[]
     */
    private $collection;

    public function __construct()
    {
        $this->collection = new TypedCollection('Star\Component\Sprint\Entity\Person');
    }

    /**
     * @param Person $person
     */
    public function add($person)
    {
        $this->collection->add($person);
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
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Person|null
     */
    public function findOneByName($name)
    {
        foreach ($this->collection as $person) {
            if ($person->getName() === $name) {
                return $person;
            }
        }

        return null;
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
}
 