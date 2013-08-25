<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Tests\Stub\Entity\StubIdentifier;

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
    private $teamMembers;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name        = $name;
        $this->teamMembers = array();
    }

    /**
     * Add a $member to the team.
     *
     * @param Member $member
     *
     * @return \Star\Component\Sprint\Entity\TeamMember
     */
    public function addMember(Member $member)
    {
        $teamMember = new TeamMember($member, $this);
        $this->teamMembers[] = $teamMember;

        return $teamMember;
    }

    /**
     * Remove the $member.
     *
     * @param Member $member
     */
    public function removeMember(Member $member)
    {
        foreach ($this->teamMembers as $key => $teamMember) {
            if ($teamMember->getMember() === $member) {
                unset($this->teamMembers[$key]);
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
        return $this->teamMembers;
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
     * @return IdentifierInterface
     */
    public function getIdentifier()
    {
        // @todo use slugify algorithm
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
        return $this->id;
        //return $this->getIdentifier()->getKey();
    }
}
