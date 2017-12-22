<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

/**
 * Represent a member that can be linked to a team
 */
interface Member
{
    /**
     * @return MemberId
     */
    public function memberId() :MemberId;
}
