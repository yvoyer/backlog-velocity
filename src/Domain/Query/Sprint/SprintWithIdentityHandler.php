<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Port\ProjectDTO;
use Star\Component\Sprint\Domain\Port\SprintDTO;
use Star\Component\Sprint\Domain\Port\TeamDTO;

final class SprintWithIdentityHandler
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

    public function __invoke(SprintWithIdentity $query, Deferred $promise)
    {
        $sql = <<<SQL
SELECT s.*, t.name AS team_name, p.name AS project_name, (
  SELECT count(c.id) 
  FROM backlog_commitments AS c 
  WHERE c.sprint_id = s.id
) as commitments
FROM backlog_sprints AS s
INNER JOIN backlog_teams as t ON t.id = s.team_id
INNER JOIN backlog_projects as p ON p.id = s.project_id
WHERE s.id = :sprint_id
SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
                'sprint_id' => $query->sprintId()->toString(),
            ]
        );

        $result = $statement->fetch();
        if (empty($result)) {
            throw EntityNotFoundException::objectWithIdentity($query->sprintId());
        }

        $promise->resolve(
            new SprintDTO(
                $result['id'],
                $result['name'],
                $result['status'],
                (int) $result['estimated_velocity'],
                (int) $result['actual_velocity'],
                (int) $result['commitments'],
                new ProjectDTO($result['project_id'], $result['project_name']),
                new TeamDTO($result['team_id'], $result['team_name'])
            )
        );
    }
}
