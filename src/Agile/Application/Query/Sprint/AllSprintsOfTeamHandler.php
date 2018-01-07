<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;
use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;

final class AllSprintsOfTeamHandler
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

    public function __invoke(AllSprintsOfTeam $query, Deferred $promise)
    {
        $sql = '
SELECT s.*, 
  CASE s.status 
    WHEN "pending" THEN 1 
    WHEN "started" THEN 2 
    WHEN "closed" THEN 3 
  END as status_order
FROM backlog_sprints AS s
WHERE s.team_id = :team_id
ORDER BY status_order
';

        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
                'team_id' => $query->teamId()->toString(),
            ]
        );

        $promise->resolve(
            array_map(
                function (array $_row) {
                    return new SprintDTO(
                        $_row['id'],
                        $_row['name'],
                        $_row['status'],
                        $_row['estimated_velocity'],
                        $_row['actual_velocity'],
                        0,
                        new ProjectDTO($_row['project_id'], 'N/A'),
                        new TeamDTO($_row['team_id'], 'N/A')
                    );
                },
                $statement->fetchAll()
            )
        );
    }
}
