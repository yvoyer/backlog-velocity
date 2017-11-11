<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Entity;

use Star\Component\Sprint\Domain\Visitor\ProjectNode;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Port\TeamMemberDTO;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface TeamMember extends ProjectNode
{
    /**
     * @param PersonId $id
     *
     * @return bool
     */
    public function matchPerson(PersonId $id);

    /**
     * @return TeamMemberDTO
     */
    public function teamMemberDto();
}
