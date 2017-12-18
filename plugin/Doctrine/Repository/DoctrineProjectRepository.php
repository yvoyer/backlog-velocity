<?php declare(strict_types=1);

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Port\ProjectDTO;
use Star\Component\Sprint\Domain\Projections\AllProjectsProjection;

final class DoctrineProjectRepository extends EntityRepository implements ProjectRepository, AllProjectsProjection
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
