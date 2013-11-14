<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping;

use Doctrine\Common\Collections\ArrayCollection;
use Star\Component\Sprint\Collection\SprintCollection;
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
     * Add a $sprinter to the team.
     *
     * @param Sprinter $sprinter
     * @param integer  $availableManDays
     *
     * @return TeamMember
     */
    public function addMember(Sprinter $sprinter, $availableManDays)
    {
        $teamMember = new TeamMemberData($sprinter, $this);
        $teamMember->setAvailableManDays($availableManDays);

        $this->members[] = $teamMember;

        return $teamMember;
    }

    /**
     * Remove the $member.
     *
     * @param Sprinter $member
     */
    public function removeMember(Sprinter $member)
    {
        foreach ($this->members as $key => $teamMember) {
            if ($teamMember->getMember() === $member) {
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

    public function addSprint(Sprint $sprint)
    {
        $this->sprints->add($sprint);
    }

    /**
     * Returns the list of pasts sprints for the team.
     *
     * @return Sprint[]
     */
    public function getPastSprints()
    {
        return $this->sprints->all();
    }
}
