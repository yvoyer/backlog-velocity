<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

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
        $this->members = array();
    }

    /**
     * Add a $sprinter to the team.
     *
     * @param SprinterInterface $sprinter
     *
     * @return \Star\Component\Sprint\Entity\TeamMember
     */
    public function addMember(SprinterInterface $sprinter)
    {
        $teamMember = new TeamMember($sprinter, $this);
        $this->members[] = $teamMember;

        return $teamMember;
    }

    /**
     * Remove the $member.
     *
     * @param SprinterInterface $member
     */
    public function removeMember(SprinterInterface $member)
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
        return $this->members;
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
