<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\Member;
use Star\Component\Sprint\Entity\MemberInterface;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintInterface;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamInterface;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class that handle the creation of all objects used by the project.
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
class DefaultObjectFactory implements EntityCreatorInterface
{
    /**
     * Create a member object.
     *
     * @return MemberInterface
     */
    public function createMember()
    {
        $object = new Member();

        return $object;
    }

    /**
     * Create a sprint object.
     *
     * @return SprintInterface
     */
    public function createSprint()
    {
        $object = new Sprint('', new Team(''));

        return $object;
    }

    /**
     * Create a team object.
     *
     * @return TeamInterface
     */
    public function createTeam()
    {
        $object = new Team('');

        return $object;
    }

    /**
     * Create a SprintMember.
     *
     * @return SprintMember
     */
    public function createSprintMember()
    {
        return new SprintMember(0, 0, $this->createSprint(), $this->createTeamMember());
    }

    /**
     * Create a Sprinter.
     *
     * @return Sprinter
     */
    public function createSprinter()
    {
        return new Sprinter('');
    }

    /**
     * Create a TeamMember.
     *
     * @return TeamMember
     */
    public function createTeamMember()
    {
        $team   = $this->createTeam();
        $member = $this->createSprinter();

        return new TeamMember($member, $team);
    }

    /**
     * Create an object of $type.
     *
     * @param string $type
     *
     * @throws \InvalidArgumentException
     * @return EntityInterface
     */
    public function createObject($type)
    {
        $object = null;
        switch ($type)
        {
            case EntityCreatorInterface::TYPE_SPRINT:
                $object = $this->createSprint();
                break;
            case EntityCreatorInterface::TYPE_SPRINTER:
                $object = $this->createSprinter();
                break;
            case EntityCreatorInterface::TYPE_SPRINT_MEMBER:
                $object = $this->createSprintMember();
                break;
            case EntityCreatorInterface::TYPE_TEAM:
                $object = $this->createTeam();
                break;
            case EntityCreatorInterface::TYPE_TEAM_MEMBER:
                $object = $this->createTeamMember();
                break;
            default:
                throw new \InvalidArgumentException("The type '{$type}' is not supported.");
        }

        return $object;
    }
}
