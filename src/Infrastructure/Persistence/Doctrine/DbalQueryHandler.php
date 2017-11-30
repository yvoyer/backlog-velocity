<?php declare(strict_types=1);

namespace Star\Component\Sprint\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Statement;
use React\Promise\Deferred;

abstract class DbalQueryHandler
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

    final protected function resolveQuery(string $sql, array $parameters, Deferred $promise)
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);
        $result = $this->fetchResult($statement);

        $value = $this->emptyResult();
        if (! empty($result)) {
            $value = $this->convertToValue($result);
        }

        $promise->resolve($value);
    }

    /**
     * @param mixed $result
     *
     * @return mixed
     */
    protected abstract function convertToValue($result);

    /**
     * @return mixed
     */
    protected function emptyResult()
    {
        return null;
    }

    /**
     * @param Statement $statement
     *
     * @return mixed
     */
    protected abstract function fetchResult(Statement $statement);
}
