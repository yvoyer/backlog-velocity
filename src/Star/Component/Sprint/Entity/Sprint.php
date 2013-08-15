<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Calculator\FocusCalculator;
use Star\Component\Sprint\Tests\Stub\Entity\StubIdentifier;

/**
 * Class Sprint
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
class Sprint implements EntityInterface, SprintInterface
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
        $calculator = new FocusCalculator($this);
        return $calculator->calculate();
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

    /**
     * @return IdentifierInterface
     */
    public function getIdentifier()
    {
        return new StubIdentifier($this->name);
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'   => $this->getId(),
            'name' => $this->name,
        );
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->getIdentifier()->getKey();
    }
}