<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

interface QuerySubject
{
    /**
     * Return a collection of many things
     *
     * @return array
     */
    public function getResults();

    /**
     * Return a single result
     *
     * @return object|null
     */
    public function getOneResult();
}
