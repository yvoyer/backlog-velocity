<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;

final class DoctrineProjectRepository extends EntityRepository implements ProjectRepository
{
    /**
     * @param ProjectId $projectId
     *
     * @return Project
     * @throws \Star\Component\Identity\Exception\EntityNotFoundException
     */
    public function getProjectWithIdentity(ProjectId $projectId) :Project
    {
        $project = $this->find($projectId->toString());
        if (! $project) {
            throw EntityNotFoundException::objectWithIdentity($projectId);
        }

        return $project;
    }

    /**
     * @param Project $project
     */
    public function saveProject(Project $project) :void
    {
        $this->_em->persist($project);
        $this->_em->flush();
    }

    /**
     * @param ProjectName $name
     *
     * @return bool
     */
    public function projectExists(ProjectName $name): bool
    {
        $qb = $this->createQueryBuilder('project');
        $qb->select('project.id')
            ->andWhere($qb->expr()->eq('project.name', ':name'))
        ;
        $result = $qb->getQuery()->execute(
            [
                'name' => $name->toString(),
            ]
        );

        return ! empty($result);
    }

    /**
     * todo Move to its own class AllProjects implements Projection
     * @return ProjectDTO[]
     */
    public function allProjects()
    {
        $qb = $this->createQueryBuilder('project')
            ->select('new ' . ProjectDTO::class . '(project.id, project.name)');

        return $qb->getQuery()->execute();
    }
}
