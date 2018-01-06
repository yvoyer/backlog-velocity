<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query;

use Star\BacklogVelocity\Agile\Domain\Model\SprintStatus;

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
    public $status;

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
     * @var ProjectDTO
     */
    public $project;

    /**
     * @var TeamDTO
     */
    public $team;

    /**
     * @param string $id
     * @param string $name
     * @param string $status
     * @param int $estimatedVelocity
     * @param int $actualVelocity
     * @param int $commitments
     * @param ProjectDTO $project
     * @param TeamDTO $team
     */
    public function __construct(
        $id,
        $name,
        $status,
        $estimatedVelocity,
        $actualVelocity,
        $commitments,
        ProjectDTO $project,
        TeamDTO $team
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->estimatedVelocity = $estimatedVelocity;
        $this->actualVelocity = $actualVelocity;
        $this->commitments = $commitments;
        $this->project = $project;
        $this->team = $team;
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
}
