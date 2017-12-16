<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;

final class JoinTeamHandler
{
    /**
     * @var TeamRepository
     */
    private $teams;

    /**
     * @var PersonRepository
     */
    private $members;

    /**
     * @param TeamRepository $teams
     * @param PersonRepository $members
     */
    public function __construct(TeamRepository $teams, PersonRepository $members)
    {
        $this->teams = $teams;
        $this->members = $members;
    }

    public function __invoke(JoinTeam $command)
    {
        if (! $this->members->personWithIdExists(PersonId::fromString($command->memberId()->toString()))) {
            throw EntityNotFoundException::objectWithIdentity($command->memberId());
        }
        $team = $this->teams->getTeamWithIdentity($command->teamId());
        $team->joinMember($command->memberId());

        $this->teams->saveTeam($team);
    }
}
