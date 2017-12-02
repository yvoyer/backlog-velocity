<?php

namespace Star\Component\Sprint\Domain\Port;

use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\PersonName;

final class TeamMemberDTO
{
    /**
     * @var string
     */
    public $personId;

    /**
     * @var string
     */
    public $personName;

    /**
     * @var string
     */
    public $teamId;

    /**
     * @var string
     */
    public $teamName;

    /**
     * @param PersonId $personId
     * @param PersonName $name
     */
    public function __construct(PersonId $personId, PersonName $name)
    {
        $this->personId = $personId->toString();
        $this->personName = $name->toString();
    }

    /**
     * @return PersonId
     */
    public function personId()
    {
        return PersonId::fromString($this->personId);
    }

    /**
     * @return PersonName
     */
    public function name()
    {
        return new PersonName($this->personName);
    }

    /**
     * @param string $personId
     * @param string $personName
     * @param string $teamId
     * @param string $teamName
     *
     * @return TeamMemberDTO
     */
    public static function fromString(
        string $personId,
        string $personName,
        string $teamId,
        string $teamName
    ) {
        return new self(PersonId::fromString($personId), new PersonName($personName));
    }
}
