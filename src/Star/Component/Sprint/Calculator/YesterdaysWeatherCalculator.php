<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\Repository\SprintRepository;

/**
 * Class YesterdaysWeatherCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 *
 * Strategy used when the team members count, working conditions, sprint length do not change.
 * Usually this technique should be used when the team has a lot of statistics (Defined by Application).
 */
class YesterdaysWeatherCalculator implements VelocityCalculator
{
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
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
