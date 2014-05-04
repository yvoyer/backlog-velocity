<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping;

use Doctrine\Common\Collections\ArrayCollection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Collection\SprinterCollection;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class TeamData
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping
 */
class TeamData extends Data implements Entity, Team
{
    const LONG_NAME = __CLASS__;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TeamMember[]
     */
    private $members;

    /**
     * @var SprintCollection
     */
    private $sprints;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name    = $name;
        $this->members = new ArrayCollection();
        $this->sprints = new SprintCollection();
    }

    /**
     * Add a $person to the team.
     *
     * @param Person $person
     *
     * @return TeamMember
     */
    public function addMember(Person $person)
    {
        $teamMember = new TeamMemberData($person, $this);

        $this->members[] = $teamMember;

        return $teamMember;
    }

    /**
     * Remove the $person.
     *
     * @param Person $person
     */
    public function removeMember(Person $person)
    {
        foreach ($this->members as $key => $teamMember) {
            if ($teamMember->getPerson() === $person) {
                unset($this->members[$key]);
            }
        }
    }

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[]
     */
    public function getMembers()
    {
        return $this->members->toArray();
    }

    /**
     * Returns the team name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Returns the team available man days.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        $availableManDays = 0;
        foreach ($this->members as $member) {
            // @todo validate that the value is numeric?
            $availableManDays += $member->getAvailableManDays();
        }

        return $availableManDays;
    }

    private function addSprint(Sprint $sprint)
    {
        $this->sprints->add($sprint);
    }

    /**
     * Starts a new sprint.
     *
     * @param string  $name
     * @param integer $manDays
     * @param integer $estimatedVelocity
     */
    public function startSprint($name, $manDays, $estimatedVelocity)
    {
        $sprint = new SprintData($name, $this, $manDays, $estimatedVelocity);
        $sprint->start(new SprinterCollection());
        $this->addSprint($sprint);
    }

    /**
     * Stop a created sprint.
     *
     * @param integer $actualVelocity
     */
    public function stopSprint($actualVelocity)
    {
        $this->getCurrentSprint()->close($actualVelocity);
    }

    /**
     * Returns the list of pasts sprints for the team.
     *
     * @return Sprint[]
     */
    public function getClosedSprints()
    {
        $closedSprints = array();
        foreach ($this->sprints->all() as $sprint) {
            if ($sprint->isClosed()) {
                $closedSprints[] = $sprint;
            }
        }

        return $closedSprints;
    }

    /**
     * Returns all the team's sprints.
     *
     * @return Sprint[]
     */
    public function getSprints()
    {
        return $this->sprints->all();
    }

    /**
     * Returns the current sprint.
     *
     * @return Sprint|null
     */
    public function getCurrentSprint()
    {
        foreach ($this->sprints->all() as $sprint) {
            if ($sprint->isOpen()) {
                return $sprint;
            }
        }

        return null;
    }

    /**
     * @param string $sprinterName
     * @param int $manDays
     *
     * @return Sprinter
     */
    public function addSprinter($sprinterName, $manDays)
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::addSprinter() not implemented yet.');
    }
}
