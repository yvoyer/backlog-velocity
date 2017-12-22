<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Project;

use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Common\Application\Query;

final class TeamWithIdentity extends Query
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
    public function teamId()
    {
        return $this->teamId;
    }
}
