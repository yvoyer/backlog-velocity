<?php

namespace Star\Component\Sprint\Entity\Repository;

interface Filter
{
    /**
     * @param QuerySubject $subject
     *
     * @return array The result
     */
    public function applyFilter(QuerySubject $subject);
}
