<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Port\SprintDTO;

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
SELECT s.*, (
  SELECT count(c.id) 
  FROM backlog_commitments AS c 
  WHERE c.sprint_id = s.id
) as commitments
FROM backlog_sprints AS s
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
                $result['project_id'],
                (int) $result['commitments']
            )
        );
    }
}
