<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use Star\Component\Sprint\Domain\Handler\Command;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;

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
