<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Calculator\FocusCalculator;

/**
 * Class Sprint
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 */
class Sprint
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $manDays;

    /**
     * @var integer
     */
    private $estimatedVelocity;

    /**
     * @var integer
     */
    private $actualVelocity;

    public function __construct($name, $manDays = null, $estimatedVelocity = null, $actualVelocity = null)
    {
        $this->name              = $name;
        $this->manDays           = $manDays;
        $this->estimatedVelocity = $estimatedVelocity;
        $this->actualVelocity    = $actualVelocity;
    }

    /**
     * Returns the estimated velocity (Story point).
     *
     * @return int
     */
    public function getEstimatedVelocity()
    {
        return $this->estimatedVelocity;
    }

    /**
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity()
    {
        return $this->actualVelocity;
    }

    /**
     * Returns the available man days.
     *
     * @return int
     */
    public function getManDays()
    {
        return $this->manDays;
    }

    /**
     * Returns the focus factor (Percent).
     *
     * @return int
     */
    public function getFocusFactor()
    {
        $calculator = new FocusCalculator();
        return $calculator->calculate($this);
    }

    /**
     * Returns the sprint name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
