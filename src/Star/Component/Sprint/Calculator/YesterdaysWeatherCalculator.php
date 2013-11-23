<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Team;

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
class YesterdaysWeatherCalculator
{
    public function calculateEstimatedVelocity(SprintRepository $repository, Team $team)
    {

    }
}
 