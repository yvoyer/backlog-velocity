<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;
use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;

final class MostActiveSprintInProjectHandler
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

    /**
     * @param MostActiveSprintInProject $query
     * @param Deferred $promise
     */
    public function __invoke(MostActiveSprintInProject $query, Deferred $promise)
    {
        $sql = <<<SQL
SELECT s.*, t.name AS team_name, p.name AS project_name, (
  SELECT count(c.id) 
  FROM backlog_commitments AS c 
  WHERE c.sprint_id = s.id
) as commitments
FROM backlog_sprints AS s
INNER JOIN backlog_teams AS t ON t.id = s.team_id
INNER JOIN backlog_projects AS p ON p.id = s.project_id
WHERE s.project_id = :projectId
AND s.status IN("pending", "started")
SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
                'projectId' => $query->projectId()->toString(),
            ]
        );
        $result = $statement->fetch();

        if (empty($result)) {
            return;
        }

        $promise->resolve(
            new SprintDTO(
                $result['id'],
                $result['name'],
                $result['status'],
                (int) $result['planned_velocity'],
                (int) $result['actual_velocity'],
                (int) $result['current_focus'],
                (int) $result['commitments'],
                new ProjectDTO($result['project_id'], $result['project_name']),
                new TeamDTO($result['team_id'], $result['team_name']),
                $result['created_at'],
                $result['started_at'],
                $result['ended_at']
            )
        );
    }
}
