<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintModel;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Agile\Domain\Visitor\ProjectVisitor;

final class NullProject implements Project
{
    /**
     * @var ProjectId
     */
    private $id;

    public function __construct()
    {
        $this->id = ProjectId::fromString(uniqid('project-id-'));
    }

    public function getIdentity(): ProjectId
    {
        return $this->id;
    }

    public function createTeam(TeamId $teamId, TeamName $name): Team
    {
        return new NullTeam();
    }

    /**
     * @param SprintId $sprintId
     * @param SprintName $name
     * @param TeamId $teamId
     * @param \DateTimeInterface $createdAt
     *
     * @return Sprint
     */
    public function createSprint(
        SprintId $sprintId,
        SprintName $name,
        TeamId $teamId,
        \DateTimeInterface $createdAt
    ): Sprint {
        return SprintModel::pendingSprint(
            $sprintId,
            $name,
            $this->getIdentity(),
            $teamId,
            $createdAt
        );
    }

    public function nextName(): SprintName
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function acceptProjectVisitor(ProjectVisitor $visitor): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function name(): ProjectName
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
