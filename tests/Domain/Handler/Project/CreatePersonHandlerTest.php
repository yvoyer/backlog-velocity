<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Domain\Model\PersonModel;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\PersonCollection;

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
            "Entity of type 'Star\Component\Sprint\Domain\Entity\Person' with 'person name' equals to 'exists' already exists."
        );
        $handler(CreatePerson::fromString('id', 'exists'));
    }
}
