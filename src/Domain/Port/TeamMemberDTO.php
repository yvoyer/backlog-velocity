<?php

namespace Star\Component\Sprint\Port;

use Star\Component\Sprint\Model\Identity\PersonId;

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
     * @param string $name
     */
    public function __construct(PersonId $personId, $name)
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
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
