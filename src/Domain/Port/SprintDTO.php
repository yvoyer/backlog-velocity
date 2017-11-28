<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintName;
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
     * @param string $projectId
     * @param string $sprintId
     * @param string $name
     * @param string $status
     */
    public function __construct(
        string $projectId,
        string $sprintId,
        string $name,
        string $status
    ) {
        $this->id = $sprintId;
        $this->projectId = $projectId;
        $this->name = $name;
        $this->status = $status;
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

    /**
     * @param ProjectId $projectId
     * @param SprintId $sprintId
     * @param SprintName $name
     * @param string $status
     *
     * @return SprintDTO
     */
    public static function fromDomain(ProjectId $projectId, SprintId $sprintId, SprintName $name, string $status) :SprintDTO
    {
        return new self($projectId->toString(), $sprintId->toString(), $name->toString(), $status);
    }

    /**
     * @param Sprint $sprint
     *
     * @return SprintDTO
     */
    public static function fromAggregate(Sprint $sprint) :SprintDTO
    {
        return self::fromDomain(
            $sprint->projectId(),
            $sprint->getId(),
            $sprint->getName(),
            'TODO'
        );
    }
}
