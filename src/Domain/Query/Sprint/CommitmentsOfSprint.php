<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Query\Query;

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
    public function sprintId()
    {
        return $this->sprintId;
    }

    /**
     * @param string $sprintId
     *
     * @return CommitmentsOfSprint
     */
    public static function fromString(string $sprintId) :CommitmentsOfSprint
    {
        return new self(SprintId::fromString($sprintId));
    }
}
