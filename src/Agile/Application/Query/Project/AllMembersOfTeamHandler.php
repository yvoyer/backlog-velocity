<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Project;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\BacklogVelocity\Agile\Application\Query\TeamMemberDTO;

final class AllMembersOfTeamHandler
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

    public function __invoke(AllMembersOfTeam $query, Deferred $promise)
    {
        $sql = <<<SQL
SELECT 
  p.id AS member_id,
  p.name AS member_name
FROM backlog_team_members AS tm
INNER JOIN backlog_teams AS t ON t.id = tm.team_id
INNER JOIN backlog_persons AS p ON p.id = tm.member_id
WHERE t.id = :team_id
GROUP BY p.id, p.name
SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute(['team_id' => $query->teamId()->toString()]);
        $result = $statement->fetchAll();

        if (! empty($result)) {
            $result = array_map(
                function (array $data) {
                    return new TeamMemberDTO(
                        $data['member_id'],
                        $data['member_name']
                    );
                },
                $result
            );
        }

        $promise->resolve($result);
    }
}
