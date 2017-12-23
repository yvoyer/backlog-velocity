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

    /**
     * @return ProjectId
     */
    public function getIdentity()
    {
        return $this->id;
    }

    /**
     * @param TeamId $teamId
     * @param TeamName $name
     *
     * @return Team
     */
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
    ) :Sprint {
        return SprintModel::pendingSprint(
            $sprintId,
            $name,
            $this->getIdentity(),
            $teamId,
            $createdAt
        );
    }

    /**
     * @return SprintName
     */
    public function nextName()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return ProjectName
     */
    public function name(): ProjectName
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
