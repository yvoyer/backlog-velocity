<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Port\CommitmentDTO;

final class CommitmentsOfSprintHandler
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

    public function __invoke(CommitmentsOfSprint $query, Deferred $promise)
    {
        $sql = <<<SQL
SELECT c.*
FROM backlog_commitments as c 
WHERE c.sprint_id = :sprint_id
SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
                'sprint_id' => $query->sprintId()->toString(),
            ]
        );
        $result = $statement->fetchAll();

        if (! empty($result)) {
            $promise->resolve(
                array_map(
                    function(array $data) {
                        return CommitmentDTO::fromString(
                            $data['sprint_id'],
                            $data['member_id'],
                            (int) $data['man_days']
                        );
                    },
                    $result
                )
            );
        } else {
            $promise->resolve([]);
        }
    }
}
