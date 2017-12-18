<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Calculator;

use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ManDays;

final class AlwaysReturnsVelocity implements VelocityCalculator
{
    /**
     * @var int
     */
    private $velocity;

    /**
     * @param int $velocity
     */
    public function __construct(int $velocity)
    {
        $this->velocity = $velocity;
    }

    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param ProjectId $projectId
     * @param ManDays $availableManDays
     * @param SprintRepository $sprintRepository
     *
     * @throws \Star\Component\Sprint\Domain\Exception\InvalidArgumentException
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity(
        ProjectId $projectId,
        ManDays $availableManDays,
        SprintRepository $sprintRepository
    ) :int {
        return $this->velocity;
    }
}
