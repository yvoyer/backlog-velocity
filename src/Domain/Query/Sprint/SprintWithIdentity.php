<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Query\Query;

final class SprintWithIdentity extends Query
{
    /**
     * @var SprintId
     */
    private $id;

    /**
     * @param SprintId $id
     */
    public function __construct(SprintId $id)
    {
        $this->id = $id;
    }

    /**
     * @return SprintId
     */
    public function sprintId()
    {
        return $this->id;
    }

    /**
     * @param string $string
     *
     * @return SprintWithIdentity
     */
    public static function fromString(string $string) :SprintWithIdentity
    {
        return new self(SprintId::fromString($string));
    }
}
