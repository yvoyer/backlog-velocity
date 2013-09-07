<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

/**
 * Class SprintMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
class SprintMember
{
    /**
     * @var integer
     */
    private $actualVelocity;

    /**
     * @var integer
     */
    private $availableManDays;

    /**
     * @param integer $availableManDays
     * @param integer $actualVelocity
     */
    public function __construct($availableManDays, $actualVelocity)
    {
        $this->actualVelocity   = $actualVelocity;
        $this->availableManDays = $availableManDays;
    }

    /**
     * Returns the available man days.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        return $this->availableManDays;
    }

    /**
     * Returns the actual velocity.
     *
     * @return integer
     */
    public function getActualVelocity()
    {
        return $this->actualVelocity;
    }
}
