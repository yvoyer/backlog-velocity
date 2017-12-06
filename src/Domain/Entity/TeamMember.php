<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Entity;

use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Visitor\ProjectNode;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface TeamMember extends ProjectNode
{
    /**
     * @param MemberId $id
     *
     * @return bool
     */
    public function matchPerson(MemberId $id) :bool;

    /**
     * @return MemberId
     */
    public function memberId() :MemberId;
}
