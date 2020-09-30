<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Common\Application\Query;

final class CommitmentsOfSprint extends Query
{
    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @param SprintId $sprintId
     */
    public function __construct(SprintId $sprintId)
    {
        $this->sprintId = $sprintId;
    }

    /**
     * @return SprintId
     */
    public function sprintId(): SprintId
    {
        return $this->sprintId;
    }

    /**
     * @param string $sprintId
     *
     * @return CommitmentsOfSprint
     */
    public static function fromString(string $sprintId): CommitmentsOfSprint
    {
        return new self(SprintId::fromString($sprintId));
    }
}
