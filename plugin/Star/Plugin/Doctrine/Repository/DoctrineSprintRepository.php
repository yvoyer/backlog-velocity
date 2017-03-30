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
use Star\Component\Sprint\Model\Identity\SprintId;

/**
 * Class SprintRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Repository
 */
class DoctrineSprintRepository extends EntityRepository implements SprintRepository
{
    /**
     * @param SprintId $id
     *
     * @return Sprint
     */
    public function findOneById(SprintId $id)
    {
        return $this->findOneBy(array('id' => $id->toString()));
    }

    /**
     * todo add @param ProjectId $projectId
     *
     * @return Sprint[]
     */
    public function endedSprints()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
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
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
