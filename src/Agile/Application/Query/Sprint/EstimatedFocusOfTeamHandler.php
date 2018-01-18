<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;

final class EstimatedFocusOfTeamHandler
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var int
     */
    private $defaultFocus;

    /**
     * @param Connection $connection
     * @param int $default Default focus when no sprint exists
     */
    public function __construct(Connection $connection, int $default)
    {
        $this->connection = $connection;
        $this->defaultFocus = $default;
    }

    public function __invoke(EstimatedFocusOfTeam $query, Deferred $promise)
    {
        $sql =
            'SELECT AVG(CAST(current_focus AS INT)) AS focus
            FROM backlog_sprints
            WHERE status = "closed"
            AND team_id = :team_id
            And ended_at <= :at_date
        ';

        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
                'at_date' => $query->at()->format('Y-m-d H:i:s'),
                'team_id' => $query->teamId()->toString(),
            ]
        );

        $result = $statement->fetch();
        $focus = $result['focus'];
        if (! $focus) {
            $focus = $this->defaultFocus;
        }

        $promise->resolve(FocusFactor::fromInt((int) $focus));
    }
}
