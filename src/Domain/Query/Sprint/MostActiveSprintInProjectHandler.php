<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Port\SprintDTO;

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
SELECT s.*, (
  SELECT count(c.id) 
  FROM backlog_commitments AS c 
  WHERE c.sprint_id = s.id
) as commitments
FROM backlog_sprints AS s
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
                (int) $result['estimated_velocity'],
                (int) $result['actual_velocity'],
                $result['project_id'],
                (int) $result['commitments']
            )
        );
    }
}
