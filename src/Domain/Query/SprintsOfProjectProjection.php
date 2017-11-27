<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;

final class SprintsOfProjectProjection
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

    public function __invoke(SprintsOfProject $query, Deferred $promise)
    {
        $statement = $this->connection->prepare(
            'SELECT COUNT(*) as sprintCount FROM backlog_sprints WHERE project_id = :project_id'
        );
        $statement->execute(['project_id' => $query->projectId()->toString()]);

        $promise->resolve((int) $statement->fetch()['sprintCount']);
    }
}
