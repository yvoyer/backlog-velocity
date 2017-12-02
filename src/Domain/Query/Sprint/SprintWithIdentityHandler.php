<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Doctrine\DBAL\Driver\Statement;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Port\SprintDTO;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandler;

final class SprintWithIdentityHandler extends DbalQueryHandler
{
    /**
     * @var SprintId
     */
    private $sprintId;

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

        $this->sprintId = $query->sprintId();
        $this->resolveQuery(
            $sql,
            [
                'sprint_id' => $query->sprintId()->toString(),
            ],
            $promise
        );
    }

    protected function emptyResult()
    {
        throw EntityNotFoundException::objectWithIdentity($this->sprintId);
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
            $result['project_id'],
            (int) $result['commitments']
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
}
