<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Person;
use Traversable;

/**
 * Class PersonCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class PersonCollection implements \Countable, \IteratorAggregate
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
    public function add(Person $person)
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
}
 