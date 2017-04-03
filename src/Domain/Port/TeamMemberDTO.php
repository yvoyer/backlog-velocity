<?php

namespace Star\Component\Sprint\Port;

use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\PersonName;

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
