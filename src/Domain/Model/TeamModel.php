<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Domain\Event\PersonJoinedTeam;
use Star\Component\Sprint\Domain\Event\TeamWasCreated;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Entity\TeamMember;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamModel extends AggregateRoot implements Team
{
    /**
     * @var TeamId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Collection|TeamMember[]
     */
    private $teamMembers;

    /**
     * @var Collection|Sprint[]
     */
    private $sprints; //todo remove, cross bounded context

    /**
     * Returns the unique id.
     *
     * @return TeamId
     */
    public function getId()
    {
        return TeamId::fromString($this->id);
    }

    public function aggregateId()
    {
        return $this->getId()->toString();
    }

    /**
     * @return TeamId[]
     *
     * @internal For read only purpose only
     */
    public function sprints()
    {
        return $this->sprints->map(
            function (Team $team) {
                return $team->getId();
            }
        )->getValues();
    }

    /**
     * @return MemberId[]
     *
     * @internal For read only purpose only
     */
    public function members()
    {
        return $this->teamMembers->map(
            function (TeamMember $member) {
                return $member->memberId();
            }
        )->getValues();
    }

    /**
     * Returns the Name.
     *
     * @return TeamName
     */
    public function getName()
    {
        return new TeamName($this->name);
    }

    /**
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor)
    {
        $visitor->visitTeam($this);
        foreach ($this->teamMembers as $teamMember) {
            $teamMember->acceptProjectVisitor($visitor);
        }
    }

    /**
     * @param MemberId $memberId
     *
     * @return bool
     */
    private function hasTeamMember(MemberId $memberId) :bool
    {
        return $this->teamMembers->exists(function ($key, TeamMember $member) use ($memberId) {
            return $member->matchPerson($memberId);
        });
    }

    /**
     * @param Member $member
     *
     * @throws \Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException
     *
     * @return TeamMember
     */
    public function addTeamMember(Member $member) :TeamMember
    {
        if ($this->hasTeamMember($member->memberId())) {
            throw new EntityAlreadyExistsException("Person '{$member->memberId()->toString()}' is already part of team.");
        }

        $teamMember = new TeamMemberModel($this, $member->memberId());
        $this->teamMembers[] = $teamMember;

        return $teamMember;
    }

    /**
     * @param MemberId $personId
     *
     * @return TeamMember
     */
    public function joinMember(MemberId $personId): TeamMember
    {
        return $this->addTeamMember(
            PersonModel::fromString($personId->toString(), $personId->toString()
        ));
    }

    /**
     * Returns the members of the team.
     *
     * @return MemberId[]
     */
    public function getTeamMembers() :array
    {
        return $this->teamMembers->map(function(TeamMember $member) {
            return $member->memberId();
        })->getValues();
    }

    protected function whenTeamWasCreated(TeamWasCreated $event)
    {
        $this->id = $event->teamId()->toString();
        $this->name = $event->name()->toString();
        $this->teamMembers = new ArrayCollection();
        $this->sprints = new ArrayCollection();
    }

    protected function whenPersonJoinedTeam(PersonJoinedTeam $event)
    {
        $this->addTeamMember(
            new PersonModel(PersonId::fromString($event->memberId()->toString()), $event->memberName())
        );
    }

    /**
     * @param TeamId $teamId
     * @param TeamName $name
     *
     * @return TeamModel
     */
    public static function create(TeamId $teamId, TeamName $name)
    {
        return self::fromStream([TeamWasCreated::version1($teamId, $name)]);
    }

    /**
     * @param string $id
     * @param string $name
     *
     * @return Team
     */
    public static function fromString(string $id, string $name) :Team
    {
        return self::fromStream(
            [
                TeamWasCreated::version1(
                    TeamId::fromString($id),
                    new TeamName($name)
                )
            ]
        );
    }

    /**
     * @param array $events
     *
     * @return static
     */
    public static function fromStream(array $events)
    {
        return self::reconstituteFromHistory(new \ArrayIterator($events));
    }
}
