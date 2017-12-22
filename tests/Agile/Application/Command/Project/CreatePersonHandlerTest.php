<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\PersonCollection;

final class CreatePersonHandlerTest extends TestCase
{
    /**
     * @var CreatePersonHandler
     */
    private $handler;

    /**
     * @var PersonCollection
     */
    private $persons;

    public function setUp()
    {
        $this->handler = new CreatePersonHandler(
            $this->persons = new PersonCollection()
        );
    }

    public function test_it_should_create_person()
    {
        $handler = $this->handler;
        $this->assertCount(0, $this->persons);

        $handler(CreatePerson::fromString('id', 'name'));

        $this->assertCount(1, $this->persons);
    }

    public function test_it_should_throw_exception_when_person_with_name_already_exists()
    {
        $this->persons->savePerson(PersonModel::fromString('id', 'exists'));
        $handler = $this->handler;
        $this->expectException(EntityAlreadyExistsException::class);
        $this->expectExceptionMessage(
            "Entity of type 'Star\BacklogVelocity\Agile\Domain\Model\Person' with 'person name' equals to 'exists' already exists."
        );
        $handler(CreatePerson::fromString('id', 'exists'));
    }
}
