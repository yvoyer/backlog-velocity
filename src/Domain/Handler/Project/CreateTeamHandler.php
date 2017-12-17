<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Domain\Model\TeamModel;

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
