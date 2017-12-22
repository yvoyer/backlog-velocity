<?php

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\Adapter;

use Doctrine\Common\Collections\Collection;
use Star\BacklogVelocity\Agile\Domain\Model\QuerySubject;

final class CollectionAdapter implements QuerySubject
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Return a collection of many things
     *
     * @return array
     */
    public function getResults()
    {
        return $this->collection->getValues();
    }

    /**
     * Return a single result
     *
     * @return object|null
     */
    public function getOneResult()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
