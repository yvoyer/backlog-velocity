<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Project;

use Doctrine\DBAL\Driver\Connection;
use React\Promise\Deferred;
use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;

final class AllProjectsHandler
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

    public function __invoke(AllProjects $query, Deferred $promise)
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM backlog_projects ORDER BY name'
        );
        $statement->execute();
        $promise->resolve(
            array_map(
                function ($data) {
                    return new ProjectDTO($data['id'], $data['name']);
                },
                $statement->fetchAll()
            )
        );
    }
}

