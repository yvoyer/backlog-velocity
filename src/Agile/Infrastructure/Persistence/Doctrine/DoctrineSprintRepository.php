<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Filter;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;

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

    /**
     * @param TeamId $teamId
     * @param \DateTimeInterface $before
     *
     * @return FocusFactor[]
     */
    public function estimatedFocusOfPastSprints(TeamId $teamId, \DateTimeInterface $before): array
    {
        $sql = 'SELECT NEW Star\BacklogVelocity\Agile\Domain\Model\FocusFactor(sprint.currentFocus)
            FROM Star\BacklogVelocity\Agile\Domain\Model\SprintModel AS sprint
            WHERE sprint.status = :status
            AND sprint.team = :team_id
            AND sprint.endedAt <= :at_date
        ';

        $query = $this->_em->createQuery($sql);
        return $query->execute(
            [
                'status' => 'closed',
                'at_date' => $before->format('Y-m-d H:i:s'),
                'team_id' => $teamId->toString(),
            ]
        );
    }
}
