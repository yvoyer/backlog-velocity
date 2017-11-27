<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Entity\Repository\Filter;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrineSprintRepository extends EntityRepository implements SprintRepository
{
    /**
     * @param ProjectId $projectId
     * @param SprintName $name
     *
     * @return Sprint
     * @throws EntityNotFoundException
     */
    public function sprintWithName(ProjectId $projectId, SprintName $name)
    {
        $sprint = $this->findOneBy(
            [
                'name' => $name->toString(),
                'project' => $projectId->toString(),
            ]
        );

        if (! $sprint) {
            throw EntityNotFoundException::objectWithAttribute(Sprint::class, 'name', $name->toString());
        }

        return $sprint;
    }

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint[]
     */
    public function endedSprints(ProjectId $projectId)
    {
        $qb = $this->createQueryBuilder('sprint');
        $qb->andWhere($qb->expr()->eq('sprint.project', ':project_id'));
        $qb->andWhere($qb->expr()->isNotNull('sprint.endedAt'));
        $qb->setParameter('project_id', $projectId->toString());

        return $qb->getQuery()->execute();
    }

    /**
     * @param Sprint $sprint
     */
    public function saveSprint(Sprint $sprint)
    {
        $this->_em->persist($sprint);
        $this->_em->flush();
    }

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint|null
     */
    public function activeSprintOfProject(ProjectId $projectId)
    {
        $qb = $this->createQueryBuilder('sprint');
        $qb->andWhere($qb->expr()->eq('sprint.project', ':project_id'));
        $qb->andWhere($qb->expr()->in('sprint.status', ['pending', 'started']));
        $qb->setParameter('project_id', $projectId->toString());
        // todo optimize query to make sure it returns the only sprint
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Filter $filter
     *
     * @return Sprint[]
     */
    public function allSprints(Filter $filter)
    {
        return $filter->applyFilter(new DoctrineFilterAdapter($this->createQueryBuilder('sprint'), 'sprint'));
    }

    /**
     * @param SprintId $sprintId
     *
     * @return Sprint
     * @throws EntityNotFoundException
     */
    public function getSprintWithIdentity(SprintId $sprintId): Sprint
    {
        $qb = $this->createQueryBuilder('sprint');
        $qb->andWhere($qb->expr()->eq('sprint.id', ':sprint_id'));
        $qb->setParameter('sprint_id', $sprintId->toString());
        $sprint = $qb->getQuery()->getOneOrNullResult();

        if (! $sprint) {
            throw EntityNotFoundException::objectWithIdentity($sprintId);
        }

        return $sprint;
    }
}
