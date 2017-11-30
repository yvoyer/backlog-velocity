<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Model\SprintStatus;

final class SprintDTO
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    public $projectId;

    /**
     * @var int
     */
    public $estimatedVelocity = -1;

    /**
     * @var int
     */
    public $actualVelocity = -1;

    /**
     * @param string $id
     * @param string $name
     * @param string $status
     * @param string $projectId
     * @param int $estimatedVelocity
     * @param int $actualVelocity
     */
    public function __construct(
        string $id,
        string $name,
        string $status,
        int $estimatedVelocity,
        int $actualVelocity,
        string $projectId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->projectId = $projectId;
        $this->estimatedVelocity = $estimatedVelocity;
        $this->actualVelocity = $actualVelocity;
    }

    public function status() :string
    {
        return $this->status;
    }

    public function isPending() :bool
    {
        return $this->status === SprintStatus::PENDING;
    }

    public function isClosed() :bool
    {
        return $this->status === SprintStatus::CLOSED;
    }

    public function isStarted() :bool
    {
        return $this->status === SprintStatus::STARTED;
    }
}
