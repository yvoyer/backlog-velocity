<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Sprint;

use Star\Component\Sprint\Domain\Handler\Command;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\ManDays;

final class CommitMemberToSprint extends Command
{
    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @var MemberId
     */
    private $memberId;

    /**
     * @var ManDays
     */
    private $manDays;

    /**
     * @param SprintId $sprintId
     * @param MemberId $memberId
     * @param ManDays $manDays
     */
    public function __construct(SprintId $sprintId, MemberId $memberId, ManDays $manDays)
    {
        $this->sprintId = $sprintId;
        $this->memberId = $memberId;
        $this->manDays = $manDays;
    }

    /**
     * @return SprintId
     */
    public function sprintId()
    {
        return $this->sprintId;
    }

    /**
     * @return MemberId
     */
    public function memberId()
    {
        return $this->memberId;
    }

    /**
     * @return ManDays
     */
    public function manDays()
    {
        return $this->manDays;
    }

    /**
     * @param string $sprintId
     * @param string $memberId
     * @param int $manDays
     *
     * @return CommitMemberToSprint
     */
    public static function fromString(string $sprintId, string $memberId, int $manDays) :self
    {
        return new self(SprintId::fromString($sprintId), MemberId::fromString($memberId), ManDays::fromInt($manDays));
    }
}
