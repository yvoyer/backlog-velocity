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
                function (array $result) {
                    return new SprintDTO(
                        $result['id'],
                        $result['name'],
                        $result['status'],
                        (int) $result['estimated_velocity'],
                        (int) $result['actual_velocity'],
                        (int) $result['current_focus'],
                        0,
                        new ProjectDTO($result['project_id'], 'N/A'),
                        new TeamDTO($result['team_id'], 'N/A'),
                        $result['created_at'],
                        $result['started_at'],
                        $result['ended_at']
                    );
                },
                $statement->fetchAll()
            )
        );
    }
}
