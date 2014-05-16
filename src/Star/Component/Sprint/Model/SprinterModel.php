<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Type\String;

/**
 * Class SprinterModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class SprinterModel implements SprintMember
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var String
     */
    private $id;

    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * @var Person
     */
    private $person;

    /**
     * @var integer
     */
    private $availableManDays;

    /**
     * @param Sprint  $sprint
     * @param Person  $person
     * @param integer $availableManDays
     */
    public function __construct(Sprint $sprint, Person $person, $availableManDays)
    {
        $this->id = new String($sprint->getId() . '_' . $person->getId());
        $this->sprint = $sprint;
        $this->person = $person;
        $this->availableManDays = $availableManDays;
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
     * @return int
     */
    public function getAvailableManDays()
    {
        return $this->availableManDays;
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
     * Returns the actual velocity.
     *
     * @return integer
     */
    public function getActualVelocity()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getActualVelocity() not implemented yet.');
    }

    /**
     * Returns the sprint.
     *
     * @return Sprint
     */
    public function getSprint()
    {
        return $this->sprint;
    }

    /**
     * Returns the team member.
     *
     * @return TeamMember
     */
    public function getTeamMember()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getTeamMember() not implemented yet.');
    }
}
 