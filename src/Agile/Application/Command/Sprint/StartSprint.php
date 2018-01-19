<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Common\Application\Command;

final class StartSprint extends Command
{
    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @var int
     */
    private $plannedVelocity;

    /**
     * @param SprintId $sprintId
     * @param int $plannedVelocity Replace with object
     */
    public function __construct(SprintId $sprintId, int $plannedVelocity)
    {
        $this->sprintId = $sprintId;
        $this->plannedVelocity = $plannedVelocity;
    }

    /**
     * @return SprintId
     */
    public function sprintId() :SprintId
    {
        return $this->sprintId;
    }

    /**
     * @return int
     */
    public function plannedVelocity() :int
    {
        return $this->plannedVelocity;
    }

    /**
     * @param string $sprintId
     * @param int $plannedVelocity
     *
     * @return StartSprint
     */
    public static function fromString(string $sprintId, int $plannedVelocity) :self
    {
        return new self(SprintId::fromString($sprintId), $plannedVelocity);
    }
}
