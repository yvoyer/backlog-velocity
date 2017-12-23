<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use Star\BacklogVelocity\Agile\Domain\Model\PersonId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Common\Application\Command;

final class CreatePerson extends Command
{
    /**
     * @var PersonId
     */
    private $personId;

    /**
     * @var PersonName
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

    public static function fromString(string $id, string $name) :self
    {
        return new self(PersonId::fromString($id), new PersonName($name));
    }
}
