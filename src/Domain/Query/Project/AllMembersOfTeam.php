<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Project;

use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Query\Query;

final class AllMembersOfTeam extends Query
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
