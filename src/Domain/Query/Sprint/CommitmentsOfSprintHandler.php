<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Doctrine\DBAL\Driver\Statement;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Port\CommitmentDTO;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandler;

final class CommitmentsOfSprintHandler extends DbalQueryHandler
{
    public function __invoke(CommitmentsOfSprint $query, Deferred $promise)
    {
        $sql = <<<SQL
SELECT c.*
FROM backlog_commitments as c 
WHERE c.sprint_id = :sprint_id
SQL;

        $this->resolveQuery($sql, ['sprint_id' => $query->sprintId()->toString()], $promise);
    }

    /**
     * @param mixed $result
     *
     * @return mixed
     */
    protected function convertToValue($result)
    {
        return array_map(
            function(array $data) {
                return new CommitmentDTO(
                    PersonId::fromString($data['person_id']),
                    ManDays::fromString($data['man_days'])
                );
            },
            $result
        );
    }

    /**
     * @param Statement $statement
     *
     * @return mixed
     */
    protected function fetchResult(Statement $statement)
    {
        return $statement->fetchAll();
    }

    protected function emptyResult()
    {
        return [];
    }
}
