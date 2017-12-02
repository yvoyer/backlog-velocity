<?php

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\ManDays;

final class CommitmentDTO
{
    /**
     * @var string
     */
    public $personId;

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
     * @param PersonId $personId
     * @param ManDays $manDays
     */
    public function __construct(SprintId $sprintId, PersonId $personId, ManDays $manDays)
    {
        $this->sprintId = $sprintId->toString();
        $this->personId = $personId->toString();
        $this->manDays = $manDays->toInt();
    }

    /**
     * @return PersonId
     */
    public function personId()
    {
        return PersonId::fromString($this->personId);
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
     * @param string $personId
     * @param int $manDays
     *
     * @return CommitmentDTO
     */
    public static function fromString(string $sprintId, string $personId, int $manDays) :CommitmentDTO
    {
        return new self(
            SprintId::fromString($sprintId),
            PersonId::fromString($personId),
            ManDays::fromInt($manDays)
        );
    }
}
