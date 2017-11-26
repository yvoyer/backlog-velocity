<?php

namespace Star\Component\Sprint\Domain\Entity\Repository;

/**
 * @deprecated Todo remove in favor of explicit queries
 */
interface Filter
{
    /**
     * @param QuerySubject $subject
     *
     * @return array The result
     */
    public function applyFilter(QuerySubject $subject);
}
