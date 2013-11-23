<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;

/**
 * Class SprintModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class SprintModel implements Sprint
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Team
     */
    private $team;

    /**
     * @var Sprinter[]
     */
    private $sprinters = array();

    /**
     * @param string $name
     * @param Team   $team
     */
    public function __construct($name, Team $team)// $length
    {
        $this->name = $name;
        $this->team = $team;
    }

    /**
     * Returns the Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function start()
    {}

    public function end($actualVelocity)
    {}

    public function estimateVelocity(ResourceCalculator $calculator)
    {
        return $calculator->calculateEstimatedVelocity($this->team, $this);
    }

    public function addSprinter(Person $person, $availableManDays)
    {
        // todo Check if already added by team
        // todo Check if already added as independent sprinter
        // todo if all else fails, create an independent sprinter
        $sprinter = new SprinterModel($this, $person, $availableManDays);
        $this->sprinters[] = $sprinter;

        return $sprinter;
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getId() not implemented yet.');
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::toArray() not implemented yet.');
    }

    /**
     * Returns whether the entity is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::isValid() not implemented yet.');
    }

    /**
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity()
    {
        return 0;
    }

    /**
     * Returns the available man days.
     *
     * @return int
     */
    public function getManDays()
    {
        $availableManDays = 0;
        foreach ($this->sprinters as $sprinter) {
            $availableManDays += $sprinter->getAvailableManDays();
        }

        return $availableManDays;
    }

    /**
     * @return Sprinter[]
     */
    public function getSprinters()
    {
        return $this->sprinters;
    }

    /**
     * Set the name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::setName() not implemented yet.');
    }

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::isClosed() not implemented yet.');
    }

    /**
     * Returns whether the sprint is opened
     *
     * @return boolean
     */
    public function isOpen()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::isOpen() not implemented yet.');
    }

    /**
     * Close a sprint.
     *
     * @param integer $actualVelocity
     */
    public function close($actualVelocity)
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::close() not implemented yet.');
    }

    /**
     * Returns the real focus factor.
     *
     * @return integer
     */
    public function getFocusFactor()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getFocusFactor() not implemented yet.');
    }

    /**
     * @return integer
     */
    public function getAvailableManDays()
    {
        return $this->getManDays();
    }

    /**
     * @return integer
     */
    public function getEstimatedVelocity()
    {
        return $this->estimateVelocity(new ResourceCalculator());
    }
}
 