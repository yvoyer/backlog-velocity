<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\PersonId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;

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
