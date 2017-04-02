<?php

namespace Star\Component\Sprint\Entity\Repository\Filters;

use Star\Component\Sprint\Entity\Repository\Filter;
use Star\Component\Sprint\Entity\Repository\QuerySubject;

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
