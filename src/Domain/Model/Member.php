<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Domain\Model\Identity\MemberId;

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
