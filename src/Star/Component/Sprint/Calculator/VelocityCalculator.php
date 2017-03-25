<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\Repository\SprintRepository;

/**
 * Class VelocityCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 */
interface VelocityCalculator
{
    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param int $availableManDays
     * @param SprintRepository $sprintRepository
     *
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity($availableManDays, SprintRepository $sprintRepository);
}
