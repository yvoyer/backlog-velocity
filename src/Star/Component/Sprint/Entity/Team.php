<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Team
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
class Team implements EntityInterface, TeamInterface
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
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name    = $name;
        $this->members = new ArrayCollection();
    }

    /**
     * Add a $sprinter to the team.
     *
     * @param Sprinter $sprinter
     *
     * @return \Star\Component\Sprint\Entity\TeamMember
     */
    public function addMember(Sprinter $sprinter)
    {
        $teamMember = new TeamMember($sprinter, $this);
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
}
