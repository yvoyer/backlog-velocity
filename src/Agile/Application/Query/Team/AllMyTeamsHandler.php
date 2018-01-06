<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Team;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;

final class AllMyTeamsHandler
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

    public function __invoke(AllMyTeams $query, Deferred $promise)
    {
        $sql = '
SELECT t.id, t.name 
FROM backlog_teams AS t
ORDER BY t.name ASC
';
//        INNER JOIN backlog_team_members AS tm ON tm.team_id = t.id
//        WHERE tm.member_id = :member_id

        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
  //              'member_id' => $query->memberId()->toString(),
            ]
        );

        $promise->resolve(
            array_map(
                function (array $_row) {
                    return new TeamDTO($_row['id'], $_row['name']);
                },
                $statement->fetchAll()
            )
        );
    }
}
