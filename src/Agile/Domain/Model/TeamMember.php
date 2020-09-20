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
    public function matchPerson(MemberId $id): bool;

    public function memberId(): MemberId;
}
