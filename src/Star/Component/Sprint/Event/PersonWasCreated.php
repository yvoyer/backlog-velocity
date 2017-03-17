<?php

namespace Star\Component\Sprint\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\PersonName;

final class PersonWasCreated extends AggregateChanged
{
    /**
     * @var PersonId
     */
    private $personId;

    /**
     * @var PersonName
     */
    private $personName;

    /**
     * @param PersonId $personId
     * @param PersonName $personName
     */
    private function __construct(PersonId $personId, PersonName $personName)
    {
        $this->personId = $personId;
        $this->personName = $personName;
    }

    /**
     * @param string $name
     *
     * @return PersonWasCreated
     */
    public static function version1($name)
    {
        return new self(PersonId::fromString($name), new PersonName($name));
    }
}
