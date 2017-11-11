<?php

namespace Star\Component\Sprint\Domain\Entity\Repository\Filters;

use Star\Component\Sprint\Domain\Entity\Repository\Filter;
use Star\Component\Sprint\Domain\Entity\Repository\QuerySubject;

final class AllObjects implements Filter
{
    /**
     * @param QuerySubject $subject
     *
     * @return array The result
     */
    public function applyFilter(QuerySubject $subject)
    {
        return $subject->getResults();
    }
}
