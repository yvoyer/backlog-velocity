<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

/**
 * Class SprintInterface
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface SprintInterface
{
    /**
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity();

    /**
     * Returns the available man days.
     *
     * @return int
     */
    public function getManDays();
}