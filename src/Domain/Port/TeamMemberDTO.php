<?php

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\PersonName;

final class TeamMemberDTO
{
    /**
     * @var PersonId
     */
    private $personId;

    /**
     * @var string
     */
    private $name;

    /**
     * @param PersonId $personId
     * @param PersonName $name
     */
    public function __construct(PersonId $personId, PersonName $name)
    {
        $this->personId = $personId;
        $this->name = $name;
    }

    /**
     * @return PersonId
     */
    public function personId()
    {
        return $this->personId;
    }

    /**
     * @return PersonName
     */
    public function name()
    {
        return $this->name;
    }
}
