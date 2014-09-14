<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Repository\Repository;

/**
 * Class SprintMemberRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Repository
 */
interface SprintMemberRepository extends Repository
{
    /**
     * @param Sprint $sprint
     *
     * @return SprintMember[]
     */
    public function findAllMemberOfSprint(Sprint $sprint);

    /**
     * @param Sprint $sprint
     *
     * @return SprintMember[]
     */
    public function findAllMemberNotPartOfSprint(Sprint $sprint);
}
