<?php

namespace Star\Component\Sprint\Entity\Repository;

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
