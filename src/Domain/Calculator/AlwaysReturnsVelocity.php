<?php

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\ManDays;

final class AlwaysReturnsVelocity implements VelocityCalculator
{
    /**
     * @var int
     */
    private $velocity;

    /**
     * @param int $velocity
     */
    public function __construct($velocity)
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
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity(
        ProjectId $projectId,
        ManDays $availableManDays,
        SprintRepository $sprintRepository
    ) {
        return $this->velocity;
    }
}
