<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\Filter;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\SprintName;

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
     */
    public function sprintWithName(ProjectId $projectId, SprintName $name)
    {
        return $this->findOneBy(
            [
                'name' => $name->toString(),
                'project' => $projectId->toString(),
            ]
        );
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
     * @return Sprint
     */
    public function activeSprintOfProject(ProjectId $projectId)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
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
}
