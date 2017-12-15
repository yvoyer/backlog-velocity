<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;

final class JoinTeamHandler
{
    /**
     * @var TeamRepository
     */
    private $teams;

    /**
     * @param TeamRepository $teams
     */
    public function __construct(TeamRepository $teams)
    {
        $this->teams = $teams;
    }

    public function __invoke(JoinTeam $command)
    {
        $team = $this->teams->getTeamWithIdentity($command->teamId());
        $team->joinMember($command->memberId());

        $this->teams->saveTeam($team);
    }
}
