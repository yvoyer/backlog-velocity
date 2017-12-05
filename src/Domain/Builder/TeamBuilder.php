<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;

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
     * @param ProjectBuilder $builder
     * @param TeamId $teamId
     */
    public function __construct(ProjectBuilder $builder, TeamId $teamId)
    {
        $this->builder = $builder;
        $this->teamId = $teamId;
    }

    public function joinedByMember(string $memberId) :TeamBuilder
    {
        $this->builder->withMemberInTeam($memberId, $this->teamId->toString());

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

    public function endTeam() :ProjectBuilder
    {
        return $this->builder;
    }

    public function getProject() :ProjectAggregate
    {
        return $this->builder->getProject();
    }
}
