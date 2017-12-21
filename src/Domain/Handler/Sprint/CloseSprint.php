<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Sprint;

use Star\Component\Sprint\Domain\Handler\Command;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Velocity;

final class CloseSprint extends Command
{
    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @var Velocity
     */
    private $actualVelocity;

    /**
     * @param SprintId $sprintId
     * @param Velocity $actualVelocity
     */
    public function __construct(SprintId $sprintId, Velocity $actualVelocity)
    {
        $this->sprintId = $sprintId;
        $this->actualVelocity = $actualVelocity;
    }

    /**
     * @return SprintId
     */
    public function sprintId()
    {
        return $this->sprintId;
    }

    /**
     * @return Velocity
     */
    public function actualVelocity()
    {
        return $this->actualVelocity;
    }

    /**
     * @param string $sprintId
     * @param int $actual
     *
     * @return CloseSprint
     */
    public static function fromString(string $sprintId, int $actual) :self {
        return new self(SprintId::fromString($sprintId), Velocity::fromInt($actual));
    }
}
