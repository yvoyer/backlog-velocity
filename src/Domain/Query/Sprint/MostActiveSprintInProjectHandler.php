<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Doctrine\DBAL\Driver\Statement;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Port\SprintDTO;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandler;

final class MostActiveSprintInProjectHandler extends DbalQueryHandler
{
    /**
     * @param MostActiveSprintInProject $query
     * @param Deferred $promise
     */
    public function __invoke(MostActiveSprintInProject $query, Deferred $promise)
    {
        $sql = <<<SQL
SELECT *
FROM backlog_sprints
WHERE project_id = :projectId
AND status IN("pending", "started")
SQL;

        $this->resolveQuery(
            $sql,
            [
                'projectId' => $query->projectId()->toString(),
            ],
            $promise
        );
    }

    /**
     * @param Statement $statement
     *
     * @return mixed
     */
    protected function fetchResult(Statement $statement)
    {
        return $statement->fetch();
    }

    /**
     * @param mixed $result
     *
     * @return mixed
     */
    protected function convertToValue($result)
    {
        return new SprintDTO(
            $result['id'],
            $result['name'],
            $result['status'],
            (int) $result['estimated_velocity'],
            (int) $result['actual_velocity'],
            $result['project_id']
        );
    }
}
