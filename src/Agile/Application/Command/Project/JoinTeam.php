<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Common\Application\Command;

final class JoinTeam extends Command
{
    /**
     * @var TeamId
     */
    private $teamId;

    /**
     * @var MemberId
     */
    private $memberId;

    /**
     * @param TeamId $teamId
     * @param MemberId $memberId
     */
    public function __construct(TeamId $teamId, MemberId $memberId)
    {
        $this->teamId = $teamId;
        $this->memberId = $memberId;
    }

    /**
     * @return TeamId
     */
    public function teamId()
    {
        return $this->teamId;
    }

    /**
     * @return MemberId
     */
    public function memberId()
    {
        return $this->memberId;
    }

    /**
     * @param string $teamId
     * @param string $memberId
     *
     * @return JoinTeam
     */
    public static function fromString(string $teamId, string $memberId) :JoinTeam
    {
        return new self(TeamId::fromString($teamId), MemberId::fromString($memberId));
    }
}
