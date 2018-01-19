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
    public $plannedVelocity = -1;

    /**
     * @var int
     */
    public $actualVelocity = -1;

    /**
     * @var int
     */
    public $actualFocus = -1;

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
     * @var string
     */
    private $createdAt;

    /**
     * @var string|null
     */
    private $startedAt;

    /**
     * @var string|null
     */
    private $closedAt;

    public function __construct(
        string $id,
        string $name,
        string $status,
        int $plannedVelocity,
        int $actualVelocity,
        int $actualFocus,
        int $commitments,
        ProjectDTO $project,
        TeamDTO $team,
        string $createdAt,
        string $startedAt = null,
        string $endedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->plannedVelocity = $plannedVelocity;
        $this->actualVelocity = $actualVelocity;
        $this->actualFocus = $actualFocus;
        $this->commitments = $commitments;
        $this->project = $project;
        $this->team = $team;
        $this->createdAt = $createdAt;
        $this->startedAt = $startedAt;
        $this->closedAt = $endedAt;
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
     * @return \DateTimeInterface
     */
    public function createdAt() :\DateTimeInterface
    {
        return new \DateTimeImmutable($this->createdAt);
    }

    /**
     * @return \DateTimeInterface
     */
    public function startedAt() :\DateTimeInterface
    {
        if (! $this->isStarted()) {
            throw new \RuntimeException('Sprint is not started, cannot get the started at date.');
        }

        return new \DateTimeImmutable($this->startedAt);
    }

    /**
     * @return \DateTimeInterface
     */
    public function closedAt() :\DateTimeInterface
    {
        if (! $this->isClosed()) {
            throw new \RuntimeException('Sprint is not closed, cannot get the closed at date.');
        }

        return new \DateTimeImmutable($this->closedAt);
    }
}
