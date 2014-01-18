<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;

/**
 * Class SprinterModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class SprinterModel
{
    const CLASS_NAME = __CLASS__;

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
        $this->sprint = $sprint;
        $this->person = $person;
        $this->availableManDays = $availableManDays;
    }

    public function getAvailableManDays()
    {
        return $this->availableManDays;
    }
}
 