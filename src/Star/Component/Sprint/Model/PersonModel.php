<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Id\PersonId;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Component\Sprint\Exception\SprintException;
use Star\Component\Sprint\Mapping\Entity;

/**
 * Class PersonModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class PersonModel implements Person, Entity
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var PersonId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TeamMember|TypedCollection
     */
    private $teamMembers;

    /**
     * @var SprintMember|TypedCollection
     */
    private $sprintMembers;

    /**
     * @param string $name
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function __construct($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("The name can't be empty.");
        }

        $this->name = $name;
        $this->teamMembers = new TypedCollection('Star\Component\Sprint\Entity\TeamMember');
        $this->sprintMembers = new TypedCollection('Star\Component\Sprint\Entity\SprintMember');
    }

    /**
     * @return PersonId
     */
    public function getId()
    {
        return $this->id = new PersonId($this->name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Team $team
     *
     * @return TeamMember
     */
    private function getTeamMember(Team $team)
    {
        // todo move to collection
        return $this->teamMembers->filter(function(TeamMember $teamMember) use ($team) {
                return $teamMember->getTeam() == $team;
            }
        )->first();
    }

    /**
     * @param Team $team
     *
     * @return bool
     */
    private function isMemberOfTeam(Team $team)
    {
        return (bool) $this->getTeamMember($team);
    }

    /**
     * @param Team $team
     *
     * @return TeamMember
     */
    public function joinTeam(Team $team)
    {
        if (false === $this->isMemberOfTeam($team)) {
            $this->teamMembers->add(new TeamMemberModel($team, $this));
        }

        return $this->getTeamMember($team);
    }

    /**
     * @param Sprint $sprint
     *
     * @return bool
     */
    private function isPartOfSprint(Sprint $sprint)
    {
        return (bool) $this->getSprintMember($sprint);
    }

    /**
     * @param Sprint $sprint
     *
     * @return SprintMember
     */
    private function getSprintMember(Sprint $sprint)
    {
        // todo move to collection
        return $this->sprintMembers->filter(function(SprintMember $sprintMember) use ($sprint) {
                return $sprintMember->getSprint() == $sprint;
            }
        )->first();
    }

    /**
     * @param Sprint $sprint
     * @param int $availableManDays
     *
     * @throws \Star\Component\Sprint\Exception\SprintException
     * @return SprintMember
     */
    public function joinSprint(Sprint $sprint, $availableManDays)
    {
        if ($this->isPartOfSprint($sprint)) {
            throw new SprintException('The person is already member of the sprint.');
        }

        $this->sprintMembers->add(new SprinterModel($sprint, $this, $availableManDays));

        return $this->getSprintMember($sprint);
    }

    /**
     * todo remove at some point
     * @return bool
     */
    public function isValid()
    {
        return !empty($this->name);
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
 