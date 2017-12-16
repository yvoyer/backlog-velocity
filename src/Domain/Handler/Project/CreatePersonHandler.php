<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Domain\Model\PersonModel;

final class CreatePersonHandler
{
    /**
     * @var PersonRepository
     */
    private $persons;

    /**
     * @param PersonRepository $persons
     */
    public function __construct(PersonRepository $persons)
    {
        $this->persons = $persons;
    }

    public function __invoke(CreatePerson $command)
    {
        if ($this->persons->personWithNameExists($command->name())) {
            throw EntityAlreadyExistsException::withAttribute($command->personId(), $command->name());
        }

        $person = new PersonModel($command->personId(), $command->name());
        $this->persons->savePerson($person);
    }
}
