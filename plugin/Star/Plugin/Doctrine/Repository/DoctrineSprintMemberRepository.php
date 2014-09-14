<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Star\Component\Sprint\Collection\SprintMemberCollection;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class DoctrineSprintMemberRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Repository
 */
class DoctrineSprintMemberRepository extends DoctrineRepository implements SprintMemberRepository
{
    /**
     * @param Sprint $sprint
     *
     * @return SprintMember[]
     */
    public function findAllMemberOfSprint(Sprint $sprint)
    {
        // todo tests
        return $this->getRepository()->findBy(array(
                'sprint' => $sprint,
            )
        );
    }

    /**
     * @param Sprint $sprint
     *
     * @return SprintMember[]
     */
    public function findAllMemberNotPartOfSprint(Sprint $sprint)
    {
        // todo tests
        $returnedMembers = array();
        /**
         * @var SprintMember[] $sprintMembers
         */
        $sprintMembers = $this->getRepository()->findAll();

        foreach ($sprintMembers as $sprintMember) {
            if ($sprintMember->getSprint() != $sprint) {
                $returnedMembers[] = $sprintMember;
            }
        }

        return $returnedMembers;
    }
}
