<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

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
