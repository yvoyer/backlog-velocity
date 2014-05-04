<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Sprinter;
use Traversable;

/**
 * Class SprinterCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class SprinterCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var TypedCollection|Sprinter[]
     */
    private $collection;

    public function __construct()
    {
        $this->collection = new TypedCollection('Star\Component\Sprint\Entity\Sprinter');
    }

    /**
     * @param Sprinter $sprinter
     */
    public function addSprinter(Sprinter $sprinter)
    {
        $this->collection[] = $sprinter;
    }

    /**
     * @return int|void
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
 