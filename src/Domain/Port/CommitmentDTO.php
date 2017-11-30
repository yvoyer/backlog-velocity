<?php

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\ManDays;

final class CommitmentDTO
{
    /**
     * @var PersonId
     */
    private $personId;

    /**
     * @var ManDays
     */
    private $manDays;

    /**
     * @param PersonId $personId
     * @param ManDays $manDays
     */
    public function __construct(PersonId $personId, ManDays $manDays)
    {
        $this->personId = $personId;
        $this->manDays = $manDays;
    }

    /**
     * @return PersonId
     */
    public function personId()
    {
        return $this->personId;
    }

    /**
     * @return ManDays
     */
    public function manDays()
    {
        return $this->manDays;
    }

    /**
     * @param string $personId
     * @param int $manDays
     *
     * @return CommitmentDTO
     */
    public static function fromString(string $personId, int $manDays) :CommitmentDTO
    {

    }
}
