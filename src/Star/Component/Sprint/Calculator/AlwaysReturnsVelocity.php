<?php

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\Repository\SprintRepository;

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
     * @param int $availableManDays
     * @param SprintRepository $sprintRepository
     *
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity($availableManDays, SprintRepository $sprintRepository)
    {
        return $this->velocity;
    }
}
