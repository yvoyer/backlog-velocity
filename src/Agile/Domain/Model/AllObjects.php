<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

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
