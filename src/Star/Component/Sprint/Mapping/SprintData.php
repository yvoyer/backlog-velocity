<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping;

use Star\Component\Sprint\Calculator\FocusCalculator;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class Sprint
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping
 */
class SprintData extends Data implements Sprint
{
    const LONG_NAME = __CLASS__;

    const STATUS_PENDING = 0;
    const STATUS_STARTED = 1;
    const STATUS_CLOSED = 2;

    /**
     * @var integer
     */
    private $id;

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

    /**
     * @var Team
     */
    private $team;

    /**
     * @var int
     */
    private $status = self::STATUS_PENDING;

    public function __construct($name, Team $team, $manDays = null, $estimatedVelocity = null, $actualVelocity = null)
    {
        $this->name              = $name;
        $this->manDays           = $manDays;
        $this->estimatedVelocity = $estimatedVelocity;
        $this->actualVelocity    = $actualVelocity;
        $this->team              = $team;
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
     * Set the name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
        return $this->id;
    }

    /**
     * Returns the team
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Returns the value on which to validate against.
     *
     * @return mixed
     */
    protected function getValue()
    {
        return $this->name;
    }

    /**
     * @return Constraint
     */
    protected function getValidationConstraints()
    {
        return new NotBlank();
    }

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Start a sprint.
     */
    public function start()
    {
        $this->status = self::STATUS_STARTED;
    }

    /**
     * Stop the sprint.
     */
    public function close($actualVelocity)
    {
        $this->actualVelocity = $actualVelocity;
        $this->status = self::STATUS_CLOSED;
    }

    /**
     * Returns whether the sprint is opened
     *
     * @return boolean
     */
    public function isOpen()
    {
        return $this->status === self::STATUS_STARTED;
    }
}
