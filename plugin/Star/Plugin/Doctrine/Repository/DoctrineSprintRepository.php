<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;

/**
 * Class SprintRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Repository
 */
class DoctrineSprintRepository extends DoctrineRepository implements SprintRepository
{
    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }

    /**
     * @return Sprint[]
     */
    public function findNotStartedSprints()
    {
        //todo tests
        return $this->findOneBy(array(
                'status' => Sprint::STATUS_INACTIVE,
            )
        );
    }

    /**
     * @param Team $team
     *
     * @return Sprint[]
     */
    public function findNotStartedSprintsOfTeam(Team $team)
    {
        //todo tests
        return $this->getRepository()->findBy(array(
                'status' => Sprint::STATUS_INACTIVE,
                'team' => $team,
            )
        );
    }
}
