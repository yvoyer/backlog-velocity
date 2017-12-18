<?php declare(strict_types=1);

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;
use Star\Component\Sprint\Domain\Entity\Repository\QuerySubject;

final class DoctrineFilterAdapter implements QuerySubject
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var string
     */
    private $alias;

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $alias
     */
    public function __construct(QueryBuilder $queryBuilder, $alias)
    {
        $this->queryBuilder = $queryBuilder;
        $this->alias = $alias;
    }

    /**
     * Return a collection of many things
     *
     * @return array
     */
    public function getResults()
    {
        return $this->queryBuilder->getQuery()->execute();
    }

    /**
     * Return a single result
     *
     * @return object|null
     */
    public function getOneResult()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
