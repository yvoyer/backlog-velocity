<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Builder;

use Prooph\Common\Messaging\DomainEvent;
use Star\BacklogVelocity\Agile\Domain\Model\Event\PersonJoinedTeam;
use Star\BacklogVelocity\Agile\Domain\Model\Event\TeamWasCreated;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;

final class TeamBuilder
{
    /**
     * @var ProjectBuilder
     */
    private $builder;

    /**
     * @var TeamId
     */
    private $teamId;

    /**
     * @var DomainEvent[]
     */
    private $events = [];

    /**
     * @param TeamWasCreated $event
     * @param ProjectBuilder $builder
     */
    public function __construct(TeamWasCreated $event, ProjectBuilder $builder)
    {
        $this->teamId = $event->teamId();
        $this->builder = $builder;
        $this->events[] = $event;
    }

    public function joinedByMember(string $memberId) :TeamBuilder
    {
        $this->events[] = PersonJoinedTeam::version1(
            $this->builder->currentProjectId(),
            MemberId::fromString($memberId),
            new PersonName($memberId),
            $this->teamId
        );

        return $this;
    }

    /**
     * @param array $members
     *
     * @return TeamBuilder
     */
    public function joinedByMembers(array $members) :TeamBuilder
    {
        foreach ($members as $member) {
            $this->joinedByMember($member);
        }

        return $this;
    }

    public function getTeam() :Team
    {
        return TeamModel::fromStream($this->events);
    }

    public function endTeam() :ProjectBuilder
    {
        return $this->builder;
    }

    public function getProject() :Project
    {
        return $this->builder->getProject();
    }
}
