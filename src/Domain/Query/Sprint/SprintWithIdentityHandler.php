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
        $statement = $this->connection->prepare(
            'SELECT * FROM backlog_sprints WHERE id = :sprint_id'
        );
        $statement->execute(['sprint_id' => $query->sprintId()->toString()]);

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
                $result['project_id']
            )
        );
    }
}
