<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Project;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Port\TeamDTO;

final class AllTeamsHandler
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

    public function __invoke(AllTeams $query, Deferred $promise)
    {
        $statement = $this->connection->prepare("SELECT * FROM backlog_teams ORDER BY name");
        $statement->execute();

        $result = $statement->fetchAll();
        $promise->resolve(
            array_map(
                function ($data) {
                    return new TeamDTO($data['id'], $data['name']);
                },
                $result
            )
        );
    }
}
