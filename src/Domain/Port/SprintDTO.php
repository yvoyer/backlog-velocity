<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintName;

final class SprintDTO
{
    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @var SprintName
     */
    private $name;

    /**
     * @var bool
     */
    private $isStarted;

    /**
     * @var bool
     */
    private $isPending;

    /**
     * @param string $projectId
     * @param string $sprintId
     * @param string $name
     * @param $status
     */
    public function __construct(
        $projectId,
        $sprintId,
        $name//,
//        /* todo SprintStatus */$status
    ) {
        $this->projectId = ProjectId::fromString($projectId);
        $this->sprintId = SprintId::fromString($sprintId);
        $this->name = new SprintName($name);
       // $this->isStarted = $isStarted;
        //$this->isPending = $isPending;
    }

    /**
     * @return string
     */
    public function projectId()
    {
        return $this->projectId->toString();
    }

    /**
     * @return string
     */
    public function sprintId()
    {
        return $this->sprintId->toString();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name->toString();
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
  //      return $this->isStarted;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
//        return $this->isPending;
    }

    /**
     * @param ProjectId $projectId
     * @param SprintId $sprintId
     * @param SprintName $name
     *
     * @return SprintDTO
     */
    public static function fromDomain(ProjectId $projectId, SprintId $sprintId, SprintName $name) :SprintDTO
    {
        return new self($projectId->toString(), $sprintId->toString(), $name->toString());
    }

    /**
     * @param Sprint $sprint
     *
     * @return SprintDTO
     */
    public static function fromAggregate(Sprint $sprint) :SprintDTO
    {
        return self::fromDomain($sprint->projectId(), $sprint->getId(), $sprint->getName());
    }
}
