<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Star\BacklogVelocity\Agile\Domain\Model\Event\NotImplementEventCallback;
use Star\BacklogVelocity\Agile\Domain\Model\Event\PersonJoinedTeam;
use Star\BacklogVelocity\Agile\Domain\Model\Event\TeamWasCreated;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Visitor\ProjectVisitor;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamModel extends AggregateRoot implements Team
{
    /**
     * @var string
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

    public function getId(): TeamId
    {
        return TeamId::fromString($this->id);
    }

    public function aggregateId(): string
    {
        return $this->getId()->toString();
    }

    /**
     * @return TeamId[]
     *
     * @internal For read only purpose only
     */
    public function sprints(): array
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
    public function members(): array
    {
        return $this->teamMembers->map(
            function (TeamMember $member) {
                return $member->memberId();
            }
        )->getValues();
    }

    public function getName(): TeamName
    {
        return new TeamName($this->name);
    }

    public function acceptProjectVisitor(ProjectVisitor $visitor): void
    {
        $visitor->visitTeam($this);
        foreach ($this->teamMembers as $teamMember) {
            $teamMember->acceptProjectVisitor($visitor);
        }
    }

    private function hasTeamMember(MemberId $memberId): bool
    {
        return $this->teamMembers->exists(function ($key, TeamMember $member) use ($memberId) {
            return $member->matchPerson($memberId);
        });
    }

    public function addTeamMember(Member $member): TeamMember
    {
        if ($this->hasTeamMember($member->memberId())) {
            throw new EntityAlreadyExistsException("Person '{$member->memberId()->toString()}' is already part of team.");
        }

        $teamMember = new TeamMemberModel($this, $member->memberId());
        $this->teamMembers[] = $teamMember;

        return $teamMember;
    }

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
    public function getTeamMembers(): array
    {
        return $this->teamMembers->map(function(TeamMember $member) {
            return $member->memberId();
        })->getValues();
    }

    protected function whenTeamWasCreated(TeamWasCreated $event): void
    {
        $this->id = $event->teamId()->toString();
        $this->name = $event->name()->toString();
        $this->teamMembers = new ArrayCollection();
        $this->sprints = new ArrayCollection();
    }

    protected function whenPersonJoinedTeam(PersonJoinedTeam $event): void
    {
        $this->addTeamMember(
            new PersonModel(PersonId::fromString($event->memberId()->toString()), $event->memberName())
        );
    }

    public static function create(TeamId $teamId, TeamName $name): Team
    {
        return self::fromStream([TeamWasCreated::version1($teamId, $name)]);
    }

    public static function fromString(string $id, string $name): Team
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
    public static function fromStream(array $events): Team
    {
        return self::reconstituteFromHistory(new \ArrayIterator($events));
    }

	protected function apply(AggregateChanged $event): void {
		if ($event instanceof TeamWasCreated) {
			$this->whenTeamWasCreated($event);
			return;
		}

    	if ($event instanceof PersonJoinedTeam) {
		    $this->whenPersonJoinedTeam($event);
		    return;
	    }

		throw new NotImplementEventCallback($event);
	}
}
