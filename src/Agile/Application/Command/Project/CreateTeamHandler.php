<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;

final class CreateTeamHandler
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

    public function __invoke(CreateTeam $command)
    {
        if ($this->teams->teamWithNameExists($command->name())) {
            throw EntityAlreadyExistsException::withAttribute($command->teamId(), $command->name());
        }

        $team = TeamModel::create($command->teamId(), $command->name());

        $this->teams->saveTeam($team);
    }
}
