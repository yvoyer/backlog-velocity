<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Common\Application\Query;

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
