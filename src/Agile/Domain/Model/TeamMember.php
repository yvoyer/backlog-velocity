<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface TeamMember
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
