<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Project;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Port\TeamDTO;

final class TeamWithIdentityHandler
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(TeamWithIdentity $query, Deferred $promise)
    {
        $teamId = $query->teamId();
        $statement = $this->connection->prepare('SELECT * FROM backlog_teams WHERE id = :team_id');
        $statement->execute(['team_id' => $teamId->toString()]);
        $team = $statement->fetch();
        if (empty($team)) {
            throw EntityNotFoundException::objectWithIdentity($teamId);
        }

        $promise->resolve(TeamDTO::fromString($team['id'], $team['name']));
    }
}
