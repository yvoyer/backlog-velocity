<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;

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
