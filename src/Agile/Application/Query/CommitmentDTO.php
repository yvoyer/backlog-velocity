<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query;

use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;

final class CommitmentDTO
{
    /**
     * @var string
     */
    public $memberId;

    /**
     * @var string
     */
    public $sprintId;

    /**
     * @var int
     */
    public $manDays;

    /**
     * @param SprintId $sprintId
     * @param MemberId $memberId
     * @param ManDays $manDays
     */
    public function __construct(SprintId $sprintId, MemberId $memberId, ManDays $manDays)
    {
        $this->sprintId = $sprintId->toString();
        $this->memberId = $memberId->toString();
        $this->manDays = $manDays->toInt();
    }

    /**
     * @return MemberId
     */
    public function memberId()
    {
        return MemberId::fromString($this->memberId);
    }

    /**
     * @return ManDays
     */
    public function manDays()
    {
        return ManDays::fromInt($this->manDays);
    }

    /**
     * @return SprintId
     */
    public function sprintId()
    {
        return SprintId::fromString($this->sprintId);
    }

    /**
     * @param string $sprintId
     * @param string $memberId
     * @param int $manDays
     *
     * @return CommitmentDTO
     */
    public static function fromString(string $sprintId, string $memberId, int $manDays) :CommitmentDTO
    {
        return new self(
            SprintId::fromString($sprintId),
            MemberId::fromString($memberId),
            ManDays::fromInt($manDays)
        );
    }
}
