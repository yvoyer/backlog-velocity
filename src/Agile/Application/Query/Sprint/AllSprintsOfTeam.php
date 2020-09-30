<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Common\Application\Query;

final class AllSprintsOfTeam extends Query
{
    /**
     * @var TeamId
     */
    private $teamId;

    /**
     * @param TeamId $teamId
     */
    public function __construct(TeamId $teamId)
    {
        $this->teamId = $teamId;
    }

    /**
     * @return TeamId
     */
    public function teamId(): TeamId
    {
        return $this->teamId;
    }
}
