<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use Star\Component\Sprint\Domain\Handler\Command;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\PersonName;

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
