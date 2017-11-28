<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Model\SprintStatus;
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
        $statement = $this->connection->prepare(
            '
SELECT id, project_id, name, status
FROM backlog_sprints
WHERE project_id = :projectId
AND status IN("pending", "started")'
        );
        $statement->execute(
            [
                'projectId' => $query->projectId()->toString(),
            ]
        );
        $result = $statement->fetch();

        $sprint = null;
        if (! empty($result)) {
            $sprint = new SprintDTO(
                $result['project_id'],
                $result['id'],
                $result['name'],
                $result['status']
            );
        }

        $promise->resolve($sprint);
    }
}
