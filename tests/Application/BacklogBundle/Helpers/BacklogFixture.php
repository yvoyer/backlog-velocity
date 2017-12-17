<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Helpers;

use Doctrine\ORM\EntityManagerInterface;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Event\ProjectWasCreated;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Domain\Model\Velocity;
use Star\Component\Sprint\Domain\Port\CommitmentDTO;

final class BacklogFixture
{
    /**
     * @var EntityManagerInterface
     * todo Remove in favor of command bus
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function team(string $team) :Team
    {
        $team = TeamModel::fromString($team, $team);
        $this->em->persist($team);
        $this->em->flush();

        return $team;
    }

    /**
     * @return ProjectAggregate
     */
    public function emptyProject() :ProjectAggregate
    {
        $project = ProjectAggregate::fromStream(
            [
                ProjectWasCreated::version1(ProjectId::uuid(), new ProjectName(uniqid('name-'))),
            ]
        );

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }

    /**
     * @param ProjectId $projectId
     * @param TeamId $teamId
     *
     * @return SprintModel
     */
    public function pendingSprint(ProjectId $projectId, TeamId $teamId) :SprintModel
    {
        $sprint = SprintModel::pendingSprint(
            SprintId::uuid(),
            SprintName::fixture(),
            $projectId,
            $teamId,
            new \DateTime()
        );

        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }

    /**
     * @param ProjectId $id
     * @param TeamId $teamId
     * @param CommitmentDTO[] $commitments
     *
     * @return SprintModel
     */
    public function startedSprint(ProjectId $id, TeamId $teamId, array $commitments) :SprintModel
    {
        $sprint = SprintModel::startedSprint(
            SprintId::uuid(),
            SprintName::fixture(),
            $id,
            $teamId,
            Velocity::fromInt(mt_rand(10, 50)),
            $commitments
        );
        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }

    /**
     * @param ProjectId $projectId
     * @param TeamId $teamId
     * @param CommitmentDTO[] $commitments
     *
     * @return SprintModel
     */
    public function closedSprint(ProjectId $projectId, TeamId $teamId, array $commitments) :SprintModel
    {
        $sprint = SprintModel::closedSprint(
            SprintId::uuid(),
            SprintName::fixture(),
            $projectId,
            $teamId,
            Velocity::fromInt(mt_rand(10, 50)),
            Velocity::fromInt(mt_rand(10, 50)),
            $commitments
        );
        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }
}
