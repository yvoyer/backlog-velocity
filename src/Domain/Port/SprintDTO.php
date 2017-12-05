<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
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
     * @var int
     */
    public $commitments;

    /**
     * @param string $id
     * @param string $name
     * @param string $status
     * @param int $estimatedVelocity
     * @param int $actualVelocity
     * @param string $projectId
     * @param int $commitments
     */
    public function __construct(
        string $id,
        string $name,
        string $status,
        int $estimatedVelocity,
        int $actualVelocity,
        string $projectId,
        int $commitments
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->projectId = $projectId;
        $this->estimatedVelocity = $estimatedVelocity;
        $this->actualVelocity = $actualVelocity;
        $this->commitments = $commitments;
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

    public function hasCommitments() :bool
    {
        return $this->commitments > 0;
    }

    /**
     * @return ProjectId
     */
    public function projectId() :ProjectId
    {
        return ProjectId::fromString($this->projectId);
    }
}
