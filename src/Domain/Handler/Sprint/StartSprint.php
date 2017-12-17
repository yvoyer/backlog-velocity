<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Sprint;

use Star\Component\Sprint\Domain\Handler\Command;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;

final class StartSprint extends Command
{
    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @var int
     */
    private $estimatedVelocity;

    /**
     * @param SprintId $sprintId
     * @param int $estimatedVelocity Replace with object
     */
    public function __construct(SprintId $sprintId, int $estimatedVelocity)
    {
        $this->sprintId = $sprintId;
        $this->estimatedVelocity = $estimatedVelocity;
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
    public function estimatedVelocity() :int
    {
        return $this->estimatedVelocity;
    }

    /**
     * @param string $sprintId
     * @param int $estimatedVelocity
     *
     * @return StartSprint
     */
    public static function fromString(string $sprintId, int $estimatedVelocity) :self
    {
        return new self(SprintId::fromString($sprintId), $estimatedVelocity);
    }
}
